<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\DoctrineMongoDBAdminBundle\Filter;

use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;

class ChoiceFilter extends Filter
{
    /**
     * @param ProxyQueryInterface $queryBuilder
     * @param string              $alias
     * @param string              $field
     * @param mixed               $data
     *
     * @return
     */
    public function filter(ProxyQueryInterface $queryBuilder, $alias, $field, $data)
    {
        if (!$data || !is_array($data) || !array_key_exists('type', $data) || !array_key_exists('value', $data)) {
            return;
        }

        if (is_array($data['value'])) {
            if (count($data['value']) == 0) {
                return;
            }

            if (in_array('all', $data['value'])) {
                return;
            }

            if ($data['type'] == ChoiceType::TYPE_NOT_CONTAINS) {
                $queryBuilder->field($field)->notIn($data['value']);
            } else {
                $queryBuilder->field($field)->in($data['value']);
            }

            $this->active = true;
        } else {
            if ($data['value'] === '' || $data['value'] === null || $data['value'] === false || $data['value'] === 'all') {
                return;
            }

            if ($data['type'] == ChoiceType::TYPE_NOT_CONTAINS) {
                $queryBuilder->field($field)->notEqual($data['value']);
            } else {
                $queryBuilder->field($field)->equals($data['value']);
            }

            $this->active = true;
        }
    }

    /**
     * @return array
     */
    public function getDefaultOptions()
    {
        return array();
    }

    public function getRenderSettings()
    {
        return array('Sonata\AdminBundle\Form\Type\Filter\DefaultType', array(
                'operator_type' => 'sonata_type_boolean',
                'field_type' => $this->getFieldType(),
                'field_options' => $this->getFieldOptions(),
                'label' => $this->getLabel(),
        ));
    }
}
