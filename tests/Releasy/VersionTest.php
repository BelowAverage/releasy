<?php

namespace BelowAverage\Releasy;

use PHPUnit\Framework\TestCase;

/**
 * @package BelowAverage.Releasy
 * @version 0.2.0   2017-08-14
 * @author  Jani Yli-Paavola
 * @license MIT
 */
class VersionTest extends TestCase {
    
    
    /** 
     * Examples taken from semver.org
     * @return array 
     */
    public function provideValidVersions(): array {
        return [
            ['1.2.3', 1, 2, 3, '', ''],
            ['1.0.0-alpha', 1, 0, 0, 'alpha', ''],
            ['1.0.0-alpha.1', 1, 0, 0, 'alpha.1', ''],
            ['1.0.0-0.3.7', 1, 0, 0, '0.3.7', ''],
            ['1.0.0-x.7.z.92', 1, 0, 0, 'x.7.z.92', ''],
            ['1.0.0-alpha+001', 1, 0, 0, 'alpha', '001'],
            ['1.0.0+20130313144700', 1, 0, 0, '', '20130313144700'],
            ['1.0.0-beta+exp.sha.5114f85', 1, 0, 0, 'beta', 'exp.sha.5114f85'],
        ];
    }
    
    /**
     * 
     * @return array of invalid version identifiers
     */
    public function provideInvalidVersions(): array {
        return [
            ['1'],
            ['1.1'],
            ['z'],
            ['1.z'],
            ['1.1.z'],
            ['1.1.1-0001'],
            ['1.1.1.1'],
            ['1.1.1.alpha']
        ];
    }
    
    public function testToString() {
        $versions = [
            '1.0.0',
            '1.0.0-alpha.test',
            '1.0.0-alpha.test+build',
            '1.0.0+build',
        ];
        foreach($versions as $version) {
            static::assertEquals($version, (string) Version::fromString($version));
        }
    }
    
    /**
     * @dataProvider provideValidVersions
     * @covers Version::fromString
     * @covers Version::__construct()
     * @param string $versionConstraint
     * @param int $expMajor
     * @param int $expMinor
     * @param int $expPatch
     * @param string $expPreRelease
     * @param string $expBuild
     */
    public function testFromStringWithValidVersions(string $versionConstraint, int $expMajor, int $expMinor, int $expPatch, string $expPreRelease, string $expBuild) {
        $version = Version::fromString($versionConstraint);
        $this->assertEquals($expMajor, $version->major(), 'Major version was set incorrectly - it should be the 1st parameter of __constructor');
        $this->assertEquals($expMinor, $version->minor(), 'Minor version was set incorrectly - it should be the 2ns parameter of __constructor');
        $this->assertEquals($expPatch, $version->patch(), 'Patch version was set incorrectly - it should be the 3rd parameter of __constructor');
        $this->assertEquals($expPreRelease, $version->preRelease(), 'Pre-release version was set incorrectly - it should be the 4th parameter of __constructor');
        $this->assertEquals($expBuild, $version->build(), 'Build version was set incorrectly - it should be the 5th parameter of __constructor');
    }
    
    /**
     * @depends testFromStringWithValidVersions
     */
    public function testBumpMajor() {
        // test data, string[] $before => $expected_after
        $testData = [
            '1.0.0'                     => '2.0.0',
            '1.1.0'                     => '2.0.0',
            '1.1.1'                     => '2.0.0',
            '2.0.0-rc.1'                => '2.0.0',
            '2.1.0-rc.2'                => '2.1.0',
            '1.0.0+build.12345'         => '2.0.0',
            '2.0.0-rc.1+build.12345'    => '2.0.0'
        ];
        
        foreach($testData as $before => $expAfter) {
            $current = Version::fromString($before);
            $currentAsString = (string)$current;
            $after = $current->bumpMajor();
            $this->assertEquals($expAfter, (string)$after, "$currentAsString should have become $expAfter, got " . (string)$after ." instead");
        }
        
    }
    
    /**
     * @depends testFromStringWithValidVersions
     */
    public function testBumpMinor() {
        // test data, string[] $before => $expected_after
        $testData = [
            '1.0.0'                     => '1.1.0',
            '1.1.0'                     => '1.2.0',
            '1.1.1'                     => '1.2.0',
            '2.0.0-rc.1'                => '2.0.0',
            '2.1.0-rc.2'                => '2.1.0',
            '1.0.0+build.12345'         => '1.1.0',
            '2.0.0-rc.1+build.12345'    => '2.0.0'
            
        ];
        
        foreach($testData as $before => $expAfter) {
            $current = Version::fromString($before);
            $currentAsString = (string)$current;
            $after = $current->bumpMinor();
            $this->assertEquals($expAfter, (string)$after, "$currentAsString should have become $expAfter, got " . (string)$after ." instead");
        }
        
    }
    
    /**
     * @depends testFromStringWithValidVersions
     */
    public function testBumpPatch() {
        // test data, string[] $before => $expected_after
        $testData = [
            '1.0.0'                     => '1.0.1',
            '1.0.1'                     => '1.0.2',
            '1.1.0+build.12345'         => '1.1.1',
            '2.0.0-rc.1'                => '2.0.0',
            '2.1.0-rc.2'                => '2.1.0',
            '2.0.1-rc.1+build.12345'    => '2.0.1'
        ];
        
        foreach($testData as $before => $expAfter) {
            $current = Version::fromString($before);
            $currentAsString = (string)$current;
            $after = $current->bumpPatch();
            $this->assertEquals($expAfter, (string)$after, "$currentAsString should have become $expAfter, got " . (string)$after ." instead");
        }
        
    }
    
}
