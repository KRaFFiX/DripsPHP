<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 30.03.15 - 13:09.
 */
namespace DripsPHP\Database\ORM;

use DripsPHP\Generators\ClassGenerator;
use DripsPHP\ClassLoader\Path;

/**
 * Class EntityGenerator.
 *
 * This generator is used to generate the Entity and EntityContainer classes.
 */
class EntityGenerator
{
    protected $entity;
    protected $savePath;

    /**
     * Creates a new object of the generator. Primarily a EntityJSONParser is
     * generated, which reads the JSON file and then stores the object in a
     * EntityData. This can then be used for further processing.
     *
     * @param $json_file
     * @param $entity_dir
     */
    public function __construct($json_file, $entity_dir = null)
    {
        $parser = new EntityJSONParser($json_file, $entity_dir);
        $this->savePath = $parser->getSavePath();
        $this->entity = $parser->getEntityData();
    }

    /**
     * generates the entity-class.
     * returns if the generation was successful or not.
     *
     * @return bool
     */
    public function generateEntity()
    {
        $generator = new ClassGenerator($this->entity->getName(), 'extends \\DripsPHP\\Database\\ORM\\Entity');
        $generator->addAttribute('TABLE', "'".$this->entity->getTable()."'", 'protected', true);
        $generator->setNamespace(str_replace('/', '\\', str_replace('src/', '', $this->savePath)));

        $primaries = $this->entity->getPrimaryKey();
        $attributes = '';

        foreach ($this->entity->getAttributes() as $attribute) {
            $getterMethod = '';
            $setterMethod = '';

            // references
            if ($this->entity->hasReference($attribute)) {
                $reference = new Path($this->entity->getReference($attribute));
                $refClass = $reference->getClass(true);
                // only one foreign key
                $setterMethod .= '
                if(is_a($value, '.$refClass.'::getEntity())){
                    $entityObj = $value;
                    $value = $value->get'.ucfirst($primaries[0]).'();
                }';
            }

            // not null
            if (!$this->entity->isNullable($attribute)) {
                $setterMethod .= 'if($value == NULL){ return false; }'."\n";
            }

            // type
            switch ($this->entity->getType($attribute)) {
                case Datatype::Integer:
                case Datatype::Float:
                    $setterMethod .= 'if(!is_numeric($value)){ return false; }';
                    break;
                case Datatype::Boolean:
                    $setterMethod .= 'if($value === 1 || $value === true){ $value = 1; } elseif($value === 0 || $value === false){ $value = 0; } else { return false; }';
                    $getterMethod .= 'return $this->getAttribute("'.$attribute.'") == 1;';
                    break;
                default:
                    break;
            }

            // validators
            if ($this->entity->hasValidators($attribute)) {
                $setterMethod .= '$validator = new \\DripsPHP\\Validator\\Validator; $validator->set($value); ';
                foreach ($this->entity->getValidators($attribute) as $validator => $params) {
                    $parameters = '[';
                    if (is_array($params)) {
                        foreach ($params as $param) {
                            $parameters .= "'$param',";
                        }
                        $parameters = rtrim($parameters, ',');
                    } else {
                        $parameters .= "'$params'";
                    }
                    $parameters .= ']';
                    $setterMethod .= '$validator->add("'.$validator.'", '.$parameters.');';
                }
                $setterMethod .= 'if(!$validator->validate()){ return false; }';
            }

            // references
            if ($this->entity->hasReference($attribute)) {
                $setterMethod .= '
                    if(isset($entityObj)){
                        if(!'.$refClass.'::has($entityObj)){
                            return false;
                        }
                    } else {
                        $entity = '.$refClass.'::get($value);
                        return $this->set'.ucfirst($attribute).'($entity);
                    }';
            }

            // default
            if ($this->entity->hasDefault($attribute)) {
                $attributes .= "'$attribute' => '".$this->entity->getDefault($attribute)."',";
            } else {
                $attributes .= "'$attribute' => null,";
            }

            $setterMethod .= 'return $this->setAttribute("'.$attribute.'", $value);';
            $getterMethod .= 'return $this->getAttribute("'.$attribute.'");';
            $generator->addMethod('get'.ucfirst($attribute), array(), 'public', false, $getterMethod);
            $generator->addMethod('set'.ucfirst($attribute), array('value'), 'public', false, $setterMethod);
        }

        $attributes = rtrim($attributes, ',');
        $generator->addAttribute('attributes', 'array('.$attributes.')');
        $primariesStr = '';
        foreach ($primaries as $primary) {
            $primariesStr .= "'".$primary."',";
        }
        $primariesStr = rtrim($primariesStr, ',');
        $generator->addAttribute('primary', 'array('.$primariesStr.')', 'protected', true);

        return file_put_contents($this->savePath.'/'.$this->entity->getName().'.php', $generator->generate(true)) !== false;
    }

    /**
     * generates the entity container class
     * returns if it was successful or not.
     *
     * @return bool
     */
    public function generateEntityContainer()
    {
        $container = $this->entity->getContainer();

        $generator = new ClassGenerator($container, 'extends \\DripsPHP\\Database\\ORM\\EntityContainer');
        $generator->setNamespace(str_replace('/', '\\', str_replace('src/', '', $this->savePath)));
        $generator->addAttribute('TABLE', "'".$this->entity->getTable()."'", 'protected', true);
        $generator->addAttribute('entity', "'".'\\\\'.str_replace('/', '\\\\', str_replace('src/', '', $this->savePath)).'\\\\'.$this->entity->getName()."'", 'protected', true);
        $generator->addAttribute('cache', 'null', 'protected', true);
        $generator->addAttribute('getAll', 'null', 'protected', true);
        $generator->addAttribute('dbCon', 'null', 'protected', true);

        return file_put_contents($this->savePath.'/'.$container.'.php', $generator->generate(true)) !== false;
    }
}
