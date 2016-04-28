<?php

namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Pagination
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class Pagination
{
    /**
     * @var int $limit Limit
     */
    protected $limit;

    /**
     * @var int $offset Offset
     */
    protected $offset;

    /**
     * Get limit
     *
     * @return int Limit
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set limit
     *
     * @param int $limit limit
     *
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get offset
     *
     * @return int Offset
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Set offset
     *
     * @param int $offset offset
     *
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }
}
