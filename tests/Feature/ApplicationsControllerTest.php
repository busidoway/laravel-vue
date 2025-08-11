<?php

namespace Tests\Feature;

use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class ApplicationsControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index()
    {
        $event = DB::table('events')->first();

        // Выполняем GET-запрос к методу index
        $response = $this->getJson('/api/applications_group');

        // Проверяем, что запрос выполнен успешно (статус 200)
        $response->assertStatus(200);

        // Проверяем, что данные из БД верно возвращаются и форматируются
        $response->assertJsonFragment([
            'id' => $event->id,
            'title' => $event->title,
            'title_count' => 150,
            'title_short' => substr($event->title, 0, 150),
            'title_length' => strlen($event->title),
            'count_app' => DB::table('event_applications')
                ->where('event_id', $event->id)
                ->count(),
            'date_public' => date('d.m.Y', strtotime($event->date_public)),
        ]);
    }
}
