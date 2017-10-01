<?php
namespace Hooloovoo\Database\ReplicationExtension;

/**
 * Interface ReplicationExtensionInterface
 */
interface ReplicationExtensionInterface
{
    /**
     * @return string
     */
    public function getMasterSwitch() : string;

    /**
     * @return string
     */
    public function getSlaveSwitch() : string;
}