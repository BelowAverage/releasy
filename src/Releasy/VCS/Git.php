<?php

namespace BelowAverage\Releasy\VCS;

use BelowAverage\Releasy\Version;
use BelowAverage\Releasy\VersionControl;
use Exception;
use RuntimeException;

/**
 * @package BelowAverage.Releasy
 * @version 0.1.0   2017-08-12
 * @author  Jani Yli-Paavola
 * @license MIT
 */
class Git implements VersionControl {

    /**
     * 
     * @return bool
     */
    public function checkStatus(): bool {
        if (strlen(shell_exec('git status --porcelain')) > 0) {
            echo "You must commit (or stash or revert) your changes before releasing";
            return false;
        }

        return true;
    }

    /**
     * @return int 0 if local and remote are equal, -1 if local is ahead, 1 if remote is ahead
     */
    public function compareLocalAndRemote(): int {
        $sha['local']   = shell_exec('git rev-parse @');
        $sha['remote']  = shell_exec('git rev-parse @{u}');
        $sha['base']    = shell_exec('git merge-base @ @{u}');

        if ($sha['local'] == $sha['remote']) {
            return 0;
        } elseif ($sha['local'] == $sha['base']) {
            echo "You need to pull the latest changes first.";
            return 1;
        } elseif ($sha['remote'] == $sha['base']) {
            echo "Please push all changes into remote before doing a release.";
            return -1;
        } else {
            new Exception("Unexpected condition while comparing remote and local branches.");
        }
    }

    /**
     * @return void
     */
    public function fetch() {
        exec('git fetch');
    }

    /**
     * 
     * @param Version $version
     * @return bool
     */
    public function commitTagPush(Version $version): bool {
        echo 'WOULD: git commit -m "Release of version ' . (string)$version . '"';
        echo 'WOULD: git tag -a '. (string)$version;
        echo 'WOULD: git push --follow-tags origin master';
        throw new RuntimeException(__METHOD__ . ' not implemented yet');
        return true;
    }

}
