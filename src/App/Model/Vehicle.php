<?php

namespace App\Model;

class Vehicle implements \JsonSerializable
{
    /**
     * @var int
     *
     * @SWG\Property()
     */
    private $id;

    /**
     * @var string
     *
     * @SWG\Property()
     */
    private $description;

    /**
     * Vehicle constructor.
     * @param int $id
     * @param string $description
     */
    public function __construct(int $id, string $description)
    {
        $this->id = $id;
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function jsonSerialize()
    {
        return [
            'Description' => $this->description,
            'VehicleId'   => $this->id,
        ];
    }
}
