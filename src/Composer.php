<?php

namespace CMSMS;

use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UninstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\Installer\PackageEvent;
use Composer\Json\JsonFile;
use Composer\Json\JsonManipulator;
use Composer\Package\PackageInterface;
use Composer\Script\Event;

class Composer {
    protected static function Bower(PackageEvent $event) {
        $package = static::GetPackageFromEvent($event);
        $io = $event->getIO();
        $bower = static::GetBowerPackages($package);
        foreach($bower as $item => $version) {
            $event->getIO()->write(sprintf('Installing bower dependency <comment>%s</comment> version <comment>%s</comment> for package <info>%s</info>... ', $item, $version, $package->getName()), false);
            $result = null;
            $output = null;
            exec("bower --allow-root install $item#$version", $output, $result);
            if ($result == 0) $io->write('<info>OK</info>');
            else $io->write('<error>Failed</error>');
        }
    }

    protected static function GetBowerPackages(PackageInterface $package) {
        $extra = $package->getExtra();
        if (!array_key_exists('cmsms', $extra)) return [];
        if (!array_key_exists('bower', $extra['cmsms'])) return [];
        return $extra['cmsms']['bower'];
    }

    /**
     * @param PackageEvent $event
     * @return string|false
     */
    protected static function GetModuleName(PackageInterface $package) {
        $extra = $package->getExtra();
        if (!array_key_exists('cmsms', $extra)) return false;
        if (!array_key_exists('module', $extra['cmsms'])) return false;
        return (string)$extra['cmsms']['name'];
    }

    /**
     * @param PackageEvent $event
     * @return PackageInterface
     */
    protected static function GetPackageFromEvent(PackageEvent $event) {
        $op = $event->getOperation();
        if ($op instanceof InstallOperation) {
            return $op->getPackage();
        } elseif ($op instanceof UpdateOperation) {
            return $op->getTargetPackage();
        } elseif ($op instanceof UninstallOperation) {
            return $op->getPackage();
        }
    }

    /**
     * @param PackageEvent $event
     * @return bool
     */
    protected static function IsModule(PackageEvent $event) {
        $extra = static::GetPackageFromEvent($event)->getExtra();
        if (!array_key_exists('cmsms', $extra)) return false;
        if (!array_key_exists('module', $extra['cmsms'])) return false;
        return (bool)$extra['cmsms']['module'];
    }

    protected static function Link(PackageEvent $event) {
        $package = static::GetPackageFromEvent($event);
        $io = $event->getIO();
        $io->write(sprintf('Installing <comment>module symlink</comment> for package <info>%s</info>... ', $package->getName()), false);
        $module = static::GetModuleName($package);
        $target = $event->getComposer()->getConfig()->get('vendor-dir') . DIRECTORY_SEPARATOR . $package->getName();
        $link = sprintf('%s/modules/%s', dirname($event->getComposer()->getConfig()->get('vendor-dir')), $module);
        try {
            $ret = symlink($target, $link);
            $io->writeError('<comment>symlinked</comment>... ', false);
        } catch (\Exception $e) {
            $ret = false;
        }
        if (!$ret) {
            $io->write('<error>Failed</error>');
        } else {
            $io->write('<info>OK</info>');
        }
    }

    protected static function Unlink(PackageEvent $event) {
        $package = static::GetPackageFromEvent($event);
        $event->getIO()->write(sprintf('Uninstalling <comment>module symlink</comment> for package <info>%s</info>...', $package->getName()));
    }

    public static function PrePackageInstall(PackageEvent $event) {
    }

    public static function PrePackageUninstall(PackageEvent $event) {
    }

    public static function PrePackageUpdate(PackageEvent $event) {
    }

    public static function PostPackageInstall(PackageEvent $event) {
        if (!static::IsModule($event)) return;
        static::Bower($event);
        static::Link($event);
    }

    public static function PostPackageUninstall(PackageEvent $event) {
        if (!static::IsModule($event)) return;
        static::Unlink($event);
    }

    public static function PostPackageUpdate(PackageEvent $event) {
        if (!static::IsModule($event)) return;
        static::Bower($event);
    }

    public static function PostCreateProjectCmd(Event $event) {
        static::PostRootPackageInstall($event);
    }

    public static function PostRootPackageInstall(Event $event) {
        $path = realpath(dirname(__DIR__) . '/composer.json');
        $json = new JsonFile($path);
        $content = new JsonManipulator(file_get_contents($json->getPath()));
        $content->removeMainKey('repositories');
        $content->removeMainKey('require');
        $content->removeMainKey('require-dev');
        file_put_contents($json->getPath(), $content->getContents());
    }

}