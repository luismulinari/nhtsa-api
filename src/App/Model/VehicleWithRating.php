<?php

namespace App\Model;

class VehicleWithRating extends Vehicle
{
    /**
     * @var string
     */
    private $rating;

    /**
     * Vehicle constructor.
     * @param int $id
     * @param string $description
     */
    public function __construct(int $id, string $description, string $rating)
    {
        $this->rating = $rating;

        parent::__construct($id, $description);
    }

    /**
     * @return string
     */
    public function getRating() : string
    {
        return $this->rating;
    }

    public function jsonSerialize()
    {
        return array_merge(
            [
                'CrashRating' => $this->rating
            ],
            parent::jsonSerialize()
        );
    }
}
