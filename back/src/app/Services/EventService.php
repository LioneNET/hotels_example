<?php

namespace App\Services;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class EventService
{
    /**
     * Получить список событий
     *
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function getData(array $params): LengthAwarePaginator
    {
        return Event::query()
            ->select('events.*')
            ->addSelect('users.name')
            ->leftJoin('users', 'events.user_id', '=', 'users.id')
            ->when(isset($params['period']), function (Builder $builder) use ($params) {
                [$start, $end] = $params['period'];
                $start = $this->setTimeByTimeZoneAndFormat($start, $params['timeZone']);
                $end = $this->setTimeByTimeZoneAndFormat($end, $params['timeZone']);
                $builder->whereBetween('events.updated_at', [$start, $end]);
            })
            ->when(isset($params['user_id']), fn ($q) => $q->where('user_id', $params['user_id']))
            ->when(isset($params['type']), fn ($q) => $q->where('type', $params['type']))
            ->when(isset($params['sort_by']), function (Builder $query) use ($params) {
                $params['sort_by'] === 'updated' && $query->orderBy('events.updated_at', $params['sort']);
            })->paginate($params['perPage'] ?? 16);
    }

    /**
     * Обработать временную зону
     *
     * @param string $timeString
     * @param $timeZone
     * @return string
     */
    private function setTimeByTimeZoneAndFormat(string $timeString, $timeZone): string
    {
        return Carbon::parse($timeString, $timeZone)
            ->tz('UTC')
            ->format('Y-m-d H:i:s');
    }
}
