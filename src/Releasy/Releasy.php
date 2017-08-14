<?php

namespace BelowAverage\Releasy;

/**
 * @package BelowAverage.Releasy
 * @version 0.3.1   2017-08-14
 * @author  Jani Yli-Paavola
 * @license MIT
 */
class Releasy {

    /**
     * Main method
     * 
     * @param array $arg
     */
    public function run($arg = []) {

        if (!isset($arg[1]) || !in_array($arg[1], ['init', 'major', 'minor', 'patch'])) {
            echo 'Usage: ' . $arg[0] . ' [major|minor|patch|init]';
            exit();
        }

        if ($arg[1] == 'init') {
            return $this->init();
        }

        // From this point forward, Releasy needs to be initalized, thus .semver -file must exist
        if (!file_exists(getcwd() . '/.semver')) {
            echo 'File ' . getcwd() . DIRECTORY_SEPARATOR . '.semver does not exist. Run ' . $arg[0] . ' init  first';
            exit(1);
        }
        $version = Version::fromString(file_get_contents(getcwd() . '/.semver'));
        $vcs = new VCS\Git();
        if(!$vcs->checkStatus() || $vcs->fetch() || $vcs->compareLocalAndRemote() != 0) {
            exit(1);
        }
        
        
        if($arg[1] == 'major') {
            $newVersion = $version->bumpMajor();
        }
        if($arg[1] == 'minor') {
            $newVersion = $version->bumpMinor();
        }
        if($arg[1] == 'patch') {
            $newVersion = $version->bumpPatch();
        }
        file_put_contents(getcwd() . '/.semver', (string)$newVersion);
        if($vcs->commitTagPush($newVersion)) {
            echo 'Released version ' . (string)$newVersion;
            exit(0);
        } else {
            echo PHP_EOL . 'Releasing version ' . (string)$newVersion . ' has failed.';
            $vcs->reset();
            exit(1);
        }
    }

    protected function init() {
        if(file_exists(getcwd() . '/.semver')) {
            echo 'File ' . getcwd() . DIRECTORY_SEPARATOR . '.semver already exists.';
            exit(1);
        } else {

            if (isset($arg[2])) {
                $version = Version::fromString($arg[2]);
            } else {
                $version = Version::fromString('0.0.0');
            }

            echo 'Initializing to version ' . (string) $version . ' ...';
            file_put_contents(getcwd() . '/.semver', (string) $version);
            echo ' DONE! Please commit .semver manually.';
            exit(0);
        }
    }

}
