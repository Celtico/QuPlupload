<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuPlupload\Entity;

interface PluploadMapperInterface
{
    public function find($id);

    public function findByParent($id);

    public function insert($plupload);

    public function update($plupload);

    public function remove($id);

    public function setTableName($pluploadTableName);
}
