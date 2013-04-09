<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuPlupload\Entity;

class PluploadEntity
{

    /**
     * @var int
     */
    protected $id_plupload;

    /**
     * @var int
     */
    protected $id_parent;

    /**
     * @var
     */
    protected $name;

    /**
     * @var
     */
    protected $type;

    /**
     * @var
     */
    protected $tmp_name;

    /**
     * @var
     */
    protected $error;

    /**
     * @var
     */
    protected $size;

    /**
     * @var
     */
    protected $model;

    /**
     * Get id.
     *
     * @return int
     */
    public function getIdPlupload()
    {
        return $this->id_plupload;
    }

    /**
     * Set id.
     *
     * @param int $id
     * @return int
     */
    public function setIdPlupload($id)
    {
        $this->id_plupload = (int) $id;
        return $this;
    }

    /**
     * Get id_parent.
     *
     * @return int
     */
    public function getIdParent()
    {
        return $this->id_parent;
    }

    /**
     * Set id_parent.
     *
     * @param int $id_parent
     * @return int
     */
    public function setIdParent($id_parent)
    {
        $this->id_parent = (int) $id_parent;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $Type
     * @return $this
     */
    public function setType($Type)
    {
        $this->type = (string) $Type;
        return $this;
    }

    /**
     * @return string
     */
    public function getTmpName()
    {
        return $this->tmp_name;
    }

    /**
     * @param $tmp_name
     * @return $this
     */
    public function setTmpName($tmp_name)
    {
        $this->tmp_name = (string) $tmp_name;
        return $this;
    }

    /**
     * @param $error
     * @return $this
     */
    public function setError($error)
    {
        $this->error = (int) $error;
        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = (int) $size;
        return $this;
    }

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }


    /**
     * @param $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = (string) $model;
        return $this;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

}
