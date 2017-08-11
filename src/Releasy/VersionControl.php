<?php

namespace BelowAverage\Releasy;

/**
 * VersionControl
 * 
 * @package BelowAverage.Releasy
 * @version 0.1.0   2017-08-11
 * @author  Jani Yli-Paavola
 * @license MIT
 */
interface VersionControl {
    
    /**
     * 
     */
    public function fetch();
    
    /**
     * 
     */
    public function checkStatus(): bool;
    
    /**
     * 
     */
    public function compareLocalAndRemote(): bool;
    
    /**
     * Commit, tag and push
     * 
     * @param Version $version
     */
    public function updateWithVersion(Version $version): bool;
    
}
