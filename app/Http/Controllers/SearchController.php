<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchCard {
    public int $id;
    public string $name;
    public string $description;
    public string $organizerName;
    public string $tags;
    public int $price;

    public function __construct(int $id, string $name, string $description, string $organizerName, string $tags, int $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->organizerName = $organizerName;
        $this->tags = $tags;
        $this->price = $price;
    }
}

class SearchController extends Controller
{
    private ?array $cards = null;

    private function generateCards() {
        if($this->cards == NULL)
        {
            for($i = 0; $i < 10; $i++)
            {
                $this->cards[$i] = new SearchCard(
                    $i,
                    fake()->words(4, true),
                    fake()->text(600),
                    fake()->name(),
                    fake()->words(3, true),
                    fake()->numberBetween(0, 500)
                );
            }
        }
    }

    public function get() {
        $this->generateCards(); // DEBUG:

        return view('search')->with('cards', $this->cards);
    }

    public function getConference() {
        $this->generateCards(); // DEBUG:

        $id = request()->get("id");

        // $conference = $this->cards[$id];
        // return view('conference')->with($conference);


        $conference = [
            "id" => $this->cards[$id]->id,
            "name" => $this->cards[$id]->name,
            "description" => $this->cards[$id]->description,
            "organizerName" => $this->cards[$id]->organizerName,
            "tags" => $this->cards[$id]->tags,
            "price" => $this->cards[$id]->price,
        ];

        return view('conference')->with($conference);
    }
}
