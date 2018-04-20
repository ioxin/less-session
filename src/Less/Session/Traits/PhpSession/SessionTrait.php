<?php
namespace Less\Session\Traits\PhpSession;

/**
 * Class SessionTrait
 * @package Less\Session\Traits\PhpSession
 */
trait SessionTrait
{
    /**
     * @hint method is used to start a new session or continue an existing session
     */
    protected function startSession()
    {
        session_start();
    }

    /**
     * @hint return id from session
     *
     * @return string
     */
    public function getSessionId()
    {
        return session_id();
    }
}