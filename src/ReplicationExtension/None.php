<?php
namespace Hooloovoo\Database\ReplicationExtension;

/**
 * Class None
 */
class None implements ReplicationExtensionInterface
{
    /**
     * @return string
     */
    public function getMasterSwitch() : string
    {
        return "";
    }

    /**
     * @return string
     */
    public function getSlaveSwitch() : string
    {
        return "";
    }
}