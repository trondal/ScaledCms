<?php

namespace Scc\Controller;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class ComponentHydrator extends DoctrineObject {

    /**
     * Extract values from an object using a by-value logic (this means that it uses the entity
     * API, in this case, getters)
     *
     * @param  object $object
     * @throws RuntimeException
     * @return array
     */
    protected function extractByValue($object) {
        $fieldNames = array_merge($this->metadata->getFieldNames(), $this->metadata->getAssociationNames());
        $methods = get_class_methods($object);

        $data = array();
        foreach ($fieldNames as $fieldName) {
            $getter = 'get' . ucfirst($fieldName);

            // Ignore unknown fields
            if (!in_array($getter, $methods)) {
                continue;
            }
            if ($getter == 'getClassName') {
                $value = $this->extractValue($fieldName, $object->$getter());

                $repo = $this->objectManager->getRepository($value);

                $child = $repo->findBy(array('node' => $object->getId()));
                $data[$fieldName] = $this->extractValue($fieldName, $child);
            } else {
                $data[$fieldName] = $this->extractValue($fieldName, $object->$getter());
            }
        }
        return $data;
    }

    protected function hydrateByValue(array $data, $object) {
        $object = $this->tryConvertArrayToObject($data, $object);
        $metadata = $this->metadata;

        foreach ($data as $field => $value) {
            $value = $this->handleTypeConversions($value, $metadata->getTypeOfField($field));
            $setter = 'set' . ucfirst($field);

            if ($metadata->hasAssociation($field)) {
                $target = $metadata->getAssociationTargetClass($field);

                if ($metadata->isSingleValuedAssociation($field)) {
                    if (!method_exists($object, $setter)) {
                        continue;
                    }

                    $value = $this->toOne($target, $this->hydrateValue($field, $value));
                    $object->$setter($value);
                } elseif ($metadata->isCollectionValuedAssociation($field)) {
                    $this->toMany($object, $field, $target, $value);
                }
            } else {
                if (!method_exists($object, $setter)) {
                    continue;
                }

                $object->$setter($value);
            }
        }

        return $object;
    }

}