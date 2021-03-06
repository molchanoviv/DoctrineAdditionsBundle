<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Opensoft
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Opensoft\DoctrineAdditionsBundle\Mapping\Generator;

use Doctrine\ORM\EntityManager;
use ReflectionProperty;

/**
 * Opensoft\DoctrineAdditionsBundle\Mapping\Generator\AbstractGenerator
 *
 * @author Ivan Molchanov <ivan.molchanov@opensoftdev.ru>
 */
abstract class AbstractGenerator implements GeneratorInterface
{

    /**
     * Generates an identifier for an entity.
     *
     * @param  EntityManager      $em
     * @param $entity
     * @param  ReflectionProperty $property
     * @return string
     */
    public function generate(EntityManager $em, $entity, ReflectionProperty $property)
    {
        $generated = false;
        do {
            $identifier = $this->generateIdentifier();
            $existingEntity = $em->getRepository(get_class($entity))->findOneBy(array($property->getName() => $identifier));
            if (empty($existingEntity)) {
                $generated = true;
            }
        } while (!$generated);

        return $identifier;
    }

    /**
     * Generates an identifier string
     *
     * @return string
     */
    abstract protected function generateIdentifier();
}
