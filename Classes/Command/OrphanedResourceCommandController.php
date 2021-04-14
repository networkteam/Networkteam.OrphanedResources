<?php
namespace Networkteam\OrphanedResources\Command;

use Neos\Flow\Annotations as Flow;
use Networkteam\OrphanedResources\Service\CleanupService;

/**
 * @Flow\Scope("singleton")
 */
class OrphanedResourceCommandController extends \Neos\Flow\Cli\CommandController
{
    /**
     * @var CleanupService
     * @Flow\Inject
     */
    protected $service;

    /**
     * Delete orphaned files in Data/Persistent/Resources
     *
     * @param boolean $execute Really delete them
     * @param integer $minimumAge Ignore files younger than x seconds (Default: 3600)
     */
    public function removeCommand($execute = false, $minimumAge = 3600)
    {
        $this->service->removeOrphanedResources($execute, $minimumAge);
    }
}
