<?php

declare(strict_types=1);

namespace Pharaonic\SmartEnum\Tests;

use BadMethodCallException;
use Error;
use Pharaonic\SmartEnum\Tests\Fixtures\AmbiguousStatus;
use Pharaonic\SmartEnum\Tests\Fixtures\IntStatus;
use Pharaonic\SmartEnum\Tests\Fixtures\MixedCaseStatus;
use Pharaonic\SmartEnum\Tests\Fixtures\StringStatus;
use Pharaonic\SmartEnum\Tests\Fixtures\UnitStatus;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Verifies the is<Case>() checks resolved by SmartEnum::__call().
 */
final class SmartEnumCallTest extends TestCase
{
    /**
     * @return iterable<string, array{\UnitEnum, string}>
     */
    public static function matchProvider(): iterable
    {
        foreach ([UnitStatus::class, StringStatus::class, IntStatus::class] as $enum) {
            yield "{$enum}::isPending" => [$enum::Pending, 'isPending'];
            yield "{$enum}::isActive" => [$enum::Active, 'isActive'];
            yield "{$enum}::isDisabled" => [$enum::Disabled, 'isDisabled'];
        }

        yield 'PENDING' => [MixedCaseStatus::PENDING, 'isPending'];
        yield 'IN_COOKING' => [MixedCaseStatus::IN_COOKING, 'isInCooking'];
        yield 'InProgress' => [MixedCaseStatus::InProgress, 'isInProgress'];
        yield 'onHold' => [MixedCaseStatus::onHold, 'isOnHold'];
        yield 'HTTPError' => [MixedCaseStatus::HTTPError, 'isHTTPError'];
        yield 'Level2' => [MixedCaseStatus::Level2, 'isLevel2'];
        yield 'beside an ambiguous check' => [AmbiguousStatus::Done, 'isDone'];
    }

    #[DataProvider('matchProvider')]
    public function testCheckIsTrueOnlyForTheMatchingCase(\UnitEnum $case, string $method): void
    {
        foreach ($case::cases() as $candidate) {
            $this->assertSame(
                $candidate === $case,
                $candidate->{$method}(),
                "{$candidate->name}->{$method}()",
            );
        }
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function undefinedProvider(): iterable
    {
        yield 'unknown case' => ['isArchived'];
        yield 'no is prefix' => ['pending'];
        yield 'other prefix' => ['hasPending'];
        yield 'raw upper-case name' => ['isPENDING'];
        yield 'raw snake-case name' => ['isIN_COOKING'];
        yield 'lower-case first letter' => ['isonHold'];
        yield 'title-cased acronym' => ['isHttpError'];
        yield 'all lower case' => ['isinprogress'];
        yield 'capital prefix' => ['IsPending'];
        yield 'protected enum method' => ['tag'];
    }


    #[DataProvider('undefinedProvider')]
    public function testUndefinedMethodThrows(string $method): void
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage('Call to undefined method ' . MixedCaseStatus::class . "::{$method}()");

        MixedCaseStatus::PENDING->{$method}();
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function ambiguousProvider(): iterable
    {
        yield 'IN_PROGRESS and InProgress' => ['isInProgress'];
    }

    #[DataProvider('ambiguousProvider')]
    public function testAmbiguousCheckThrows(string $method): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage(
            'Call to ambiguous method ' . AmbiguousStatus::class
            . "::{$method}(): matches cases IN_PROGRESS, InProgress.",
        );

        AmbiguousStatus::Done->{$method}();
    }

    #[DataProvider('matchProvider')]
    public function testArgumentsToACheckAreIgnored(\UnitEnum $case, string $method): void
    {
        $this->assertTrue($case->{$method}('ignored', 1));
    }
}
