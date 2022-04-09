<?php
/**
 * @brief        File Storage Extension: mods
 * @author        <a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright    (c) Invision Power Services, Inc.
 * @license        https://www.invisioncommunity.com/legal/standards/
 * @package        Invision Community
 * @subpackage    (aXen) Advanced Server List
 * @since        08 Apr 2022
 */

namespace IPS\axenserverlist\extensions\core\FileStorage;

/* To prevent PHP errors (extending class does not exist) revealing path */
if (!\defined('\IPS\SUITE_UNIQUE_KEY')) {
    header((isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0') . ' 403 Forbidden');
    exit;
}

/**
 * File Storage Extension: mods
 */
class _mods
{
    /**
     * Count stored files
     *
     * @return    int
     */
    public function count()
    {
        return \IPS\Db::i()->select('COUNT(*)', 'axenserverlist_mods', 'icon IS NOT NULL')->first();
    }

    /**
     * Move stored files
     *
     * @param    int            $offset                    This will be sent starting with 0, increasing to get all files stored by this extension
     * @param    int            $storageConfiguration    New storage configuration ID
     * @param    int|NULL    $oldConfiguration        Old storage configuration ID
     * @throws    \UnderflowException                    When file record doesn't exist. Indicating there are no more files to move
     * @return    void|int                            An offset integer to use on the next cycle, or nothing
     */
    public function move($offset, $storageConfiguration, $oldConfiguration = null)
    {
        $thing = \IPS\Db::i()->select('*', 'axenserverlist_mods', 'icon IS NOT NULL', 'id', array($offset, 1))->first();
        \IPS\Db::i()->update('axenserverlist_mods', ['image' => (string) \IPS\File::get($oldConfiguration ?: 'axenserverlist_mods', $thing['icon'])->move($storageConfiguration)], ['id=?', $thing['id']]);
    }

    /**
     * Check if a file is valid
     *
     * @param    string    $file        The file path to check
     * @return    bool
     */
    public function isValidFile($file)
    {
        try
        {
            \IPS\Db::i()->select('id', 'axenserverlist_mods', ['icon=?', $file])->first();
            return true;
        } catch (\UnderflowException$e) {
            return false;
        }
    }

    /**
     * Delete all stored files
     *
     * @return    void
     */
    public function delete()
    {
        foreach (\IPS\Db::i()->select('*', 'axenserverlist_mods', "icon IS NOT NULL") as $item) {
            try
            {
                \IPS\File::get('axenserverlist_mods', $item['icon'])->delete();
            } catch (\Exception$e) {}
        }
    }
}
