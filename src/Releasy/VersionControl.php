<?php

namespace BelowAverage\Releasy;

/**
 * VersionControl
 * 
 * @package BelowAverage.Releasy
 * @version 0.2.0   2017-08-12
 * @author  Jani Yli-Paavola
 * @license MIT
 */
interface VersionControl {
    
    /**
     * 
     */
    public function fetch();
    
    /**
     * Ensure that local working tree does not have any uncommited/unstashed changes
     */
    public function checkStatus(): bool;
    
    /**
     * @return int 0 if local and remote are equal, -1 if local is ahead, 1 if remote is ahead
     */
    public function compareLocalAndRemote(): int;
    
    /**
     * Commit, tag and push
     * 
     * @param Version $version
     */
    public function commitTagPush(Version $version): bool;
    
}
