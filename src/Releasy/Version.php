<?php

namespace BelowAverage\Releasy;

use InvalidArgumentException;

/**
 * Version
 * 
 * @package BelowAverage.Releasy
 * @version 0.2.0   2017-08-14
 * @author  Jani Yli-Paavola
 * @license MIT
 */
class Version {
    
    /** @var int */
    private $major;
    
    /** @var int */
    private $minor;
    
    /** @var int */
    private $patch;
    
    /** @var string */
    private $preRelease;
    
    /** @var string */
    private $build;
    
    /**
     * 
     * @param int $major
     * @param int $minor
     * @param int $patch
     * @param string $preRelease
     * @param string $build
     */
    public function __construct(int $major, int $minor, int $patch, string $preRelease = '', string $build = '') {
        $this->major    = $major;
        $this->minor    = $minor;
        $this->patch    = $patch;
        $this->setPreRelease($preRelease);
        $this->setBuild($build);
    }
    
    /**
     * 
     * @return string
     */
    public function __toString(): string {
        $ret = $this->major . '.' . $this->minor . '.' . $this->patch;
        if(strlen($this->preRelease) > 0) {
            $ret .= '-' . $this->preRelease;
        }
        if(strlen($this->build) > 0) {
            $ret .= '+' . $this->build;
        }
        
        return $ret;
    }
    
    /**
     * 
     * @param string $versionString
     * @return Version
     * @throws InvalidArgumentException
     */
    public static function fromString(string $versionString): Version {
        if(!preg_match('#^(?P<major>[0-9]+)\.(?P<minor>[0-9]+)\.(?P<patch>[0-9]+)(?:-(?P<prerelease>(?:[0-9A-Za-z-]+\.)*(?:[0-9A-Za-z-]+)))?(?:\+(?P<build>(?:[0-9A-Za-z-]+\.)*(?:[0-9A-Za-z-]+)))?$#', $versionString, $matches)) {
            throw new InvalidArgumentException(filter_var($versionString, FILTER_SANITIZE_FULL_SPECIAL_CHARS) . ' does not look like semver compatible version string.');
        }
        
        $defaults = [
            'major'         => intval(0),
            'minor'         => intval(0),
            'patch'         => intval(0),
            'prerelease'    => '',
            'build'         => ''
        ];
        
        $version = array_merge($defaults, $matches);
        
        return new Version(
                $version['major'], 
                $version['minor'], 
                $version['patch'], 
                $version['prerelease'], 
                $version['build']
        );        
    }
    
    /**
     * 
     * @return string
     */
    public function toJson(): string {
        return json_encode([
            'major'         => $this->major,
            'minor'         => $this->minor,
            'patch'         => $this->patch,
            'pre-release'   => $this->preRelease,
            'build'         => $this->build
        ]);
    }
    
    /**
     * 
     * @param string $json
     * @return Version
     */
    public static function fromJson($json): Version {
        $version = json_decode($json, true);
        
        return new Version(
                $version['major'], 
                $version['minor'], 
                $version['patch'], 
                $version['versionString'], 
                $version['build']
        );   
        
    }
    
    /**
     * 
     * @return int
     */
    public function major(): int {
        return $this->major;
    }

    /**
     * 
     * @return int
     */
    public function minor(): int {
        return $this->minor;
    }
    
    /**
     * 
     * @return int
     */
    public function patch(): int {
        return $this->patch;
    }

    /**
     * 
     * @return string
     */
    public function preRelease(): string {
        return $this->preRelease;
    }

    /**
     * 
     * @return string
     */
    public function build(): string {
        return $this->build;
    }
    
    /**
     * 
     * @return Version
     */
    public function bumpMajor(): Version {
        // If current version is a prelease, bumping major is the actual release of the version - thus
        // remove prerelease information
        if($this->preRelease !== '') {
            return new Version($this->major, $this->minor, $this->patch);
        }
        
        $inc = $this->major + 1;
        
        return new Version($inc, 0, 0);
    }
    
    /**
     * 
     * @return Version
     */
    public function bumpMinor(): Version {
        // If current version is a prelease, bumping minor is the actual release of the version - thus
        // remove prerelease information
        if($this->preRelease !== '') {
            return new Version($this->major, $this->minor, $this->patch);
        }
        
        $inc = $this->minor + 1;
        
        return new Version($this->major, $inc, 0);
    }
    
    /**
     * 
     * @return Version
     */
    public function bumpPatch(): Version {
        // If current version is a prelease, bumping patch is the actual release of the version - thus
        // remove prerelease information
        if($this->preRelease !== '') {
            return new Version($this->major, $this->minor, $this->patch);
        }
        
        $inc = $this->patch + 1;
        
        return new Version($this->major, $this->minor, $inc);
    }

        
    /**
     * Validates pre-release information
     * 
     * @param string $preRelease
     * @throws InvalidArgumentException
     */
    private function setPreRelease($preRelease) {
        
        if($preRelease !== '' && !preg_match('#^([0-9A-Za-z-]+\.)*[0-9A-Za-z-]+$#', $preRelease)) {
            throw new InvalidArgumentException('Pre-release information is not valid.');
        }
        
        $this->preRelease = $preRelease;
    }
    
    /**
     * 
     * Validates build information 
     * 
     * @param string $build
     * @throws InvalidArgumentException
     */
    private function setBuild($build) {
        
        if($build !== '' && !preg_match('#^([0-9A-Za-z-]+\.)*[0-9A-Za-z-]+$#', $build)) {
            throw new InvalidArgumentException('Build information is not valid.');
        }
        
        $this->build = $build;
    }
    
}
