<?php

namespace Networkteam\OrphanedResources\Service;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\ResourceManagement\PersistentResource;
use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\Utility\Files;
use Psr\Log\LoggerInterface;

/**
 * @Flow\Scope("singleton")
 */
class CleanupService
{
    /**
     * @Flow\Inject(name="Neos.Flow:SystemLogger")
     * @var LoggerInterface
     */
    protected $systemLogger;

    /**
     * @Flow\Inject
     * @var ResourceManager
     */
    protected $resourceManager;

    public function removeOrphanedResources(bool $execute = false, int $minimumAge = 3600): void
    {
        foreach (Files::getRecursiveDirectoryGenerator(FLOW_PATH_DATA . 'Persistent/Resources/') as $file) {
            if (
                is_file($file) &&
                !$this->isFileToNew($file, $minimumAge) &&
                !$this->isFileAttachedToAResourceInDatabase($file)
            ) {
                if ($execute) {
                    Files::unlink($file);
                    Files::removeEmptyDirectoriesOnPath(dirname($file));
                }

                $this->systemLogger->info(sprintf('Deleted orphaned resource: %s',
                    str_replace(FLOW_PATH_ROOT, '', $file)
                ));
            }
        }
    }

    protected function isFileToNew(string $file, int $minimumAge): bool
    {
        return filemtime($file) > time() - $minimumAge;
    }

    protected function isFileAttachedToAResourceInDatabase(string $file)
    {
        return $this->resourceManager->getResourceBySha1(basename($file)) instanceof PersistentResource;
    }
}