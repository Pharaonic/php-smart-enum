<?php

declare(strict_types=1);

namespace Pharaonic\SmartEnum\Tools;

use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\EnumCase;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use Pharaonic\SmartEnum\CaseMethods;

/**
 * Inserts @method tags for the is<Case>() checks of enums that use SmartEnum.
 *
 * Names come from CaseMethods, so the tags match the checks the trait resolves.
 * Text outside the generated section of an existing docblock is left in place.
 */
final class EnumDocBlockGenerator
{
    private const START_MARKER = '<start enum methods>';

    private const END_MARKER = '</end enum methods>';

    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public static function create(): self
    {
        return new self((new ParserFactory())->createForNewestSupportedVersion());
    }

    /**
     * @return array{changed: bool, contents: string, enums: int, error: ?string}
     */
    public function process(string $contents): array
    {
        try {
            $ast = $this->parser->parse($contents);
        } catch (Error $error) {
            return self::result(false, $contents, 0, $error->getMessage());
        }

        if ($ast === null) {
            return self::result(false, $contents, 0, null);
        }

        /** @var list<array{start: int, length: int, replacement: string}> $edits */
        $edits = [];
        $enumCount = 0;

        $visitor = new class($this->collector($edits, $enumCount)) extends NodeVisitorAbstract {
            /**
             * @param \Closure(Enum_): void $onEnum
             */
            public function __construct(private \Closure $onEnum) {}

            public function enterNode(Node $node)
            {
                if ($node instanceof Enum_) {
                    ($this->onEnum)($node);
                }

                return null;
            }
        };

        $traverser = new NodeTraverser();
        $traverser->addVisitor($visitor);
        $traverser->traverse($ast);

        $updated = $contents;

        usort(
            $edits,
            static fn(array $left, array $right): int => $right['start'] <=> $left['start'],
        );

        foreach ($edits as $edit) {
            $updated = substr_replace(
                $updated,
                $edit['replacement'],
                $edit['start'],
                $edit['length'],
            );
        }

        return self::result($updated !== $contents, $updated, $enumCount, null);
    }

    /**
     * @param list<array{start: int, length: int, replacement: string}> $edits
     *
     * @return \Closure(Enum_): void
     */
    private function collector(array &$edits, int &$enumCount): \Closure
    {
        return function (Enum_ $enum) use (&$edits, &$enumCount): void {
            if (!$this->usesSmartEnum($enum)) {
                return;
            }

            $enumCount++;

            $methods = $this->methodNames($enum);

            if ($methods === []) {
                return;
            }

            $docComment = $enum->getDocComment();
            $oldDoc = $docComment?->getText() ?? '';
            $newDoc = $this->updateDocBlock($oldDoc, $methods);

            if ($newDoc === $oldDoc) {
                return;
            }

            if ($docComment !== null) {
                $start = $docComment->getStartFilePos();
                $end = $docComment->getEndFilePos();

                if ($start < 0 || $end < $start) {
                    return;
                }

                $edits[] = [
                    'start' => $start,
                    'length' => $end - $start + 1,
                    'replacement' => $newDoc,
                ];

                return;
            }

            $start = $enum->getStartFilePos();

            if ($start < 0) {
                return;
            }

            $edits[] = [
                'start' => $start,
                'length' => 0,
                'replacement' => $newDoc . "\n",
            ];
        };
    }

    private function usesSmartEnum(Enum_ $enum): bool
    {
        foreach ($enum->stmts as $statement) {
            if (!$statement instanceof TraitUse) {
                continue;
            }

            foreach ($statement->traits as $trait) {
                $traitName = $trait->toString();

                if ($traitName === 'SmartEnum' || str_ends_with($traitName, '\\SmartEnum')) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return list<string>
     */
    private function methodNames(Enum_ $enum): array
    {
        $methods = [];

        foreach ($enum->stmts as $statement) {
            if (!$statement instanceof EnumCase) {
                continue;
            }

            $methods[] = CaseMethods::methodName($statement->name->toString());
        }

        return array_values(array_unique($methods));
    }

    /**
     * @param list<string> $methods
     */
    private function updateDocBlock(string $oldDoc, array $methods): string
    {
        $generated = $this->methodsDocBlock($methods);

        if ($oldDoc === '') {
            return "/**\n * {$generated}\n */";
        }

        $pattern = '~^[ \t]*\* '
            . preg_quote(self::START_MARKER, '~')
            . '[ \t]*\R[\s\S]*?^[ \t]*\* '
            . preg_quote(self::END_MARKER, '~')
            . '[ \t]*(?=\R|$)~m';

        if (preg_match($pattern, $oldDoc, $matches, PREG_OFFSET_CAPTURE) === 1) {
            return substr_replace(
                $oldDoc,
                ' * ' . $generated,
                $matches[0][1],
                strlen($matches[0][0]),
            );
        }

        $closingPosition = strrpos($oldDoc, '*/');

        if ($closingPosition === false) {
            return $oldDoc;
        }

        return rtrim(substr($oldDoc, 0, $closingPosition))
            . "\n * "
            . $generated
            . "\n "
            . substr($oldDoc, $closingPosition);
    }

    /**
     * @param list<string> $methods
     */
    private function methodsDocBlock(array $methods): string
    {
        $lines = [self::START_MARKER];

        foreach ($methods as $method) {
            $lines[] = "@method bool {$method}()";
        }

        $lines[] = self::END_MARKER;

        return implode("\n * ", $lines);
    }

    /**
     * @return array{changed: bool, contents: string, enums: int, error: ?string}
     */
    private static function result(bool $changed, string $contents, int $enums, ?string $error): array
    {
        return [
            'changed' => $changed,
            'contents' => $contents,
            'enums' => $enums,
            'error' => $error,
        ];
    }
}
