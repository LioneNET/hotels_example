<?php

namespace App\Http\Controllers;

use App\Enums\EventTypeEnums;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Services\EventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventController extends Controller
{
    /**
     * Получить список событий
     *
     * @param EventRequest $request
     * @param EventService $service
     * @return AnonymousResourceCollection
     */
    public function index(EventRequest $request, EventService $service): AnonymousResourceCollection
    {
        return EventResource::collection($service->getData($request->validated()));
    }

    /**
     * Типы событий
     *
     * @return JsonResponse
     */
    public function eventTypes(): JsonResponse
    {
        $data = collect(EventTypeEnums::options())
            ->map(fn ($title, $id) => ['id' => $id, 'title' => $title])
            ->values()
            ->all();

        return response()->json($data);
    }
}
