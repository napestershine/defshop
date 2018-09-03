<?php

namespace App;

/**
 * Class Catalogue
 * @package App
 */
class Catalogue extends Application
{

    /**
     * @var string
     */
    protected $_table = 'categories';

    /**
     * @var string
     */
    protected $_table_2 = 'products';

    /**
     * Catalogue constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param null $identity
     * @return bool|null
     */
    public function getCategoryByIdentity($identity = null)
    {
        if (!empty($identity)) {
            $sql = "SELECT *
			        FROM `{$this->_table}`
					WHERE `identity` = ?";
            return $this->_Db->fetchOne($sql, $identity);
        }
        return null;
    }

    /**
     * @param null $identity
     * @return bool|null
     */
    public function getProductByIdentity($identity = null)
    {
        if (!empty($identity)) {
            $sql = "SELECT *
			        FROM `{$this->_table_2}`
					WHERE `identity` = ?";
            return $this->_Db->fetchOne($sql, $identity);
        }
        return null;
    }

    /**
     * @param null $color
     * @return bool|null
     */
    public function getProductsByColor($color = null)
    {
        if (!empty($color)) {
            $sql = "SELECT *
			        FROM `{$this->_table_2}`
					WHERE `color` = ?";
            return $this->_Db->fetchAll($sql, $color);
        }
        return null;
    }

    /**
     * @return bool
     */
    public function getCategories()
    {
        $sql = "SELECT *
                FROM `{$this->_table}`
				ORDER BY `name` ASC";
        return $this->_Db->fetchAll($sql);
    }

    /**
     * @param null $id
     * @return bool|null
     */
    public function getCategory($id = null)
    {
        if (!empty($id)) {
            $sql = "SELECT `c`.*,
					(
						SELECT COUNT(`id`)
						FROM `{$this->_table_2}`
						WHERE `category` = `c`.`id`
					) AS `products_count`
					FROM `{$this->_table}` `c`
					WHERE `c`.`id` = ?";
            return $this->_Db->fetchOne($sql, $id);
        }
        return null;
    }

    /**
     * @param int|null $id
     * @return bool|null
     */
    public function getCategoryById(int $id = null)
    {
        if (!empty($id)) {
            $sql = "SELECT `c`.*
					FROM `{$this->_table}` `c`
					WHERE `c`.`id` = ?";
            return $this->_Db->fetchOne($sql, $id);
        }
        return null;
    }

    /**
     * @param null $array
     * @return bool
     */
    public function addCategory($array = null)
    {
        if (!Helper::isArrayEmpty($array)) {
            return $this->_Db->insert($this->_table, array(
                'name' => $array['name'],
                'identity' => $array['identity'],
                'meta_title' => $array['meta_title'],
                'meta_description' => $array['meta_description']
            ));
        }
        return false;
    }

    /**
     * @param null $array
     * @param null $id
     * @return bool|mixed
     */
    public function updateCategory($array = null, $id = null)
    {
        if (!Helper::isArrayEmpty($array) && !empty($id)) {
            return $this->_Db->update($this->_table, array(
                'name' => $array['name'],
                'identity' => $array['identity'],
                'meta_title' => $array['meta_title'],
                'meta_description' => $array['meta_description']
            ), $id);
        }
        return false;
    }

    /**
     * @param null $name
     * @param null $id
     * @return bool
     */
    public function duplicateCategory($name = null, $id = null)
    {
        if (!empty($name)) {
            $array = array($name);
            $sql = "SELECT *
			        FROM `{$this->_table}`
					WHERE `name` = ?";
            if (!empty($id)) {
                $array[] = $id;
                $sql .= " AND `id` != ?";
            }
            return $this->_Db->fetchOne($sql, $array);
        }
        return false;
    }

    /**
     * @param null $id
     * @return bool|mixed
     */
    public function removeCategory($id = null)
    {
        return $this->delete($id);
    }

    /**
     * @param null $cat
     * @return bool
     */
    public function getProducts($cat = null)
    {
        $sql = "SELECT *
		        FROM `{$this->_table_2}`
				WHERE `category` = ?
				ORDER BY `date` DESC";
        return $this->_Db->fetchAll($sql, $cat);
    }

    /**
     * @param null $id
     * @return bool|null
     */
    public function getProduct($id = null)
    {
        return $this->_Db->selectOne($this->_table_2, $id);
    }

    /**
     * @param null $srch
     * @return bool
     */
    public function getAllProducts($srch = null)
    {
        $array = array();
        $sql = "SELECT *
		        FROM `{$this->_table_2}`";
        if (!empty($srch)) {
            $sql .= " WHERE `name` LIKE ? || `id` = ?";
            $array[] = "%{$srch}%";
            $array[] = "%{$srch}%";
        }
        $sql .= " ORDER BY `date` DESC";
        return $this->_Db->fetchAll($sql, $array);
    }

    /**
     * @param null $srch
     * @param null $cat
     * @param string $filterBy
     * @param null $value
     * @return bool
     */
    public function getAllProductsByFilter($srch = null, $cat = null, $filterBy = 'color', $value = null)
    {
        $array = array();
        $sql = "SELECT *
		        FROM `{$this->_table_2}`";
        if (!empty($value)) {
            $sql .= " WHERE `?` = ?";
            $array[] = "{$filterBy}";
            $array[] = "{$value}";

            if (!empty($srch)) {
                $sql .= " AND ( `name` LIKE ? || `id` = ? )";
                $array[] = "%{$srch}%";
                $array[] = "%{$srch}%";
            }
            if (!empty($cat)) {
                $sql .= " AND `category` = ?";
                $array[] = "{$cat}";
            }
        }
        $sql .= " ORDER BY `date` DESC";
        return $this->_Db->fetchAll($sql, $array);
    }

    /**
     * @param null $params
     * @return bool
     */
    public function addProduct($params = null)
    {
        if ($this->_Db->insert($this->_table_2, $params)) {
            $this->id = $this->_Db->id;
            return true;
        }
        return false;
    }

    /**
     * @param null $params
     * @param null $id
     * @return bool|mixed
     */
    public function updateProduct($params = null, $id = null)
    {
        return $this->_Db->update($this->_table_2, $params, $id);
    }

    /**
     * @param null $id
     * @return bool|mixed
     */
    public function removeProduct($id = null)
    {
        if (!empty($id)) {
            $product = $this->getProduct($id);
            if (!empty($product)) {
                if (is_file(CATALOGUE_PATH . DS . $product['image'])) {
                    unlink(CATALOGUE_PATH . DS . $product['image']);
                }
                return $this->_Db->delete($this->_table_2, $id);
            }
            return false;
        }
        return false;
    }

    /**
     * @param null $identity
     * @param null $id
     * @return bool
     */
    public function isDuplicateProduct($identity = null, $id = null)
    {
        if (!empty($identity)) {
            $array = array($identity);
            $sql = "SELECT *
					FROM `{$this->_table_2}`
					WHERE `identity` = ?";
            if (!empty($id)) {
                $sql .= " AND `id` != ?";
                $array[] = $id;
            }
            $result = $this->_Db->fetchAll($sql, $array);
            return !empty($result) ? true : false;
        }
        return false;
    }

    /**
     * @param null $identity
     * @param null $id
     * @return bool
     */
    public function isDuplicateCategory($identity = null, $id = null)
    {
        if (!empty($identity)) {
            $array = array($identity);
            $sql = "SELECT *
					FROM `{$this->_table}`
					WHERE `identity` = ?";
            if (!empty($id)) {
                $sql .= " AND `id` != ?";
                $array[] = $id;
            }
            $result = $this->_Db->fetchAll($sql, $array);
            return !empty($result) ? true : false;
        }
        return false;
    }

    public function getColorsFromProducts()
    {
        $sql = "SELECT distinct(color)
		        FROM `{$this->_table_2}`";
        return $this->_Db->fetchAll($sql);
    }
}