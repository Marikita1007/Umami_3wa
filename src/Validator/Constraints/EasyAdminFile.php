<?php

namespace App\Validator\Constraints;

/**
 * Custom file validation attribute for EasyAdmin.
 *
 * This attribute extends Symfony\Component\Validator\Constraints\File and is used
 * to add custom validation constraints for file uploads in EasyAdmin forms.
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @property int $maxSize The maximum allowed file size.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class EasyAdminFile extends \Symfony\Component\Validator\Constraints\File
{
}
