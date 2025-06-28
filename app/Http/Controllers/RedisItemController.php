<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisItemController extends Controller
{
    protected string $prefix = 'redis_item:';
    protected int $ttl = 3600;
    public function index()
    {
        $keys = Redis::keys($this->prefix . '*');
        $items = [];
        foreach ($keys as $key) {
            $value = Redis::get($key);
            $items[] = [
                'key' => str_replace($this->prefix, '', $key),
                'value' => json_decode($value, true),
            ];
        }
        return response()->json(['data' => $items]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'required|array',
        ]);
        //dd($request->all());
        $key = $this->prefix . $request->key;
        Redis::setex($key, $this->ttl, json_encode($request->value));
        return response()->json(['message' => 'Stored in Redis']);
    }

    public function show(string $key)
    {
        $fullKey = $this->prefix . $key;
        $value = Redis::get($fullKey);
        if (!$value) {
            return response()->json(['message' => 'Key not found in Redis'], 404);
        }

        return response()->json([
            'key' => $key,
            'value' => json_decode($value, true),
        ]);
    }

    public function update(Request $request, string $key)
    {
        $request->validate([
            'value' => 'required|array',
        ]);

        $fullKey = $this->prefix . $key;

        if (!Redis::exists($fullKey)) {
            return response()->json(['message' => 'Key does not exist'], 404);
        }

        Redis::setex($fullKey, $this->ttl, json_encode($request->value));

        return response()->json(['message' => 'Updated in Redis']);
    }

    public function destroy(string $key)
    {
        $fullKey = $this->prefix . $key;
        Redis::del($fullKey);

        return response()->json(['message' => 'Deleted from Redis']);
    }

    public function flush()
    {
        $keys = Redis::keys($this->prefix . '*');
        foreach ($keys as $key) {
            Redis::del($key);
        }

        return response()->json(['message' => 'All Redis items deleted']);
    }
}
