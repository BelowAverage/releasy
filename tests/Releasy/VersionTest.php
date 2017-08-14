<?php

namespace BelowAverage\Releasy;

use PHPUnit\Framework\TestCase;

/**
 * @package BelowAverage.Releasy
 * @version 0.1.0   2017-08-14
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
    
}
