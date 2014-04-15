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

namespace Opensoft\DoctrineAdditionsBundle\Mapping\Driver;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Opensoft\DoctrineAdditionsBundle\Mapping\Metadata\ClassMetadata as DoctrineAdditionsClassMetadata;
use Opensoft\DoctrineAdditionsBundle\Mapping\Metadata\ClassAssociationMetadata as DoctrineAdditionsClassAssociationMetadata;
use Opensoft\DoctrineAdditionsBundle\Mapping\Annotation\AssociationPropertyOverride;

/**
 * Opensoft\DoctrineAdditionsBundle\Mapping\Driver\Annotation
 *
 * @author Ivan Molchanov <ivan.molchanov@opensoftdev.ru>
 */
class Annotation extends AnnotationDriver
{
    use DriverTrait;

    public function loadMetadataForClass($className, ClassMetadata $metadata)
    {
        parent::loadMetadataForClass($className, $metadata);
        $reflectionClass = $metadata->getReflectionClass();
        $doctrineAdditionsClassMetadata = new DoctrineAdditionsClassMetadata();
        foreach ($this->reader->getClassAnnotations($reflectionClass) as $annotation) {
            /** @var AssociationPropertyOverride $annotation */
            if ($annotation instanceof AssociationPropertyOverride) {
                $doctrineAdditionsClassAssociationMetadata = new DoctrineAdditionsClassAssociationMetadata();
                $doctrineAdditionsClassAssociationMetadata->setOrphanRemoval($annotation->isRemoveOrphans());
                $doctrineAdditionsClassAssociationMetadata->setMappedBy($annotation->getMappedBy());
                $doctrineAdditionsClassAssociationMetadata->setInversedBy($annotation->getInversedBy());
                $doctrineAdditionsClassMetadata->addAssociation($annotation->getAssociationName(), $doctrineAdditionsClassAssociationMetadata);
            }
        }

        $this->convertMetadata($doctrineAdditionsClassMetadata, $metadata);
    }
} 
