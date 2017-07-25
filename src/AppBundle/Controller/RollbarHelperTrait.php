<?php

namespace AppBundle\Controller;

/**
 * RollbarHelperTrait
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
trait RollbarHelperTrait
{
    /**
     * Send exception to Rollbar
     *
     * @param \Exception $e Exception
     */
    protected function sendExceptionToRollbar(\Exception $e)
    {
        if ($this->has('ftrrtf_rollbar.notifier')) {
            $this->get('ftrrtf_rollbar.notifier')->reportException($e);
        }
    }
}
