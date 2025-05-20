<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\JournalServiceInterface;
use App\Http\Resources\JournalResource;
use App\Http\Requests\Journal\StoreJournalRequest;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    protected $journalService;

    public function __construct(JournalServiceInterface $journalService)
    {
        $this->journalService = $journalService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = $request->query('date');
        $query = $this->journalService->all()->with('participant', 'user');
        if ($date) {
            $query->whereDate('date', $date);
        }
        return JournalResource::collection($query->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJournalRequest $request)
    {
        $journalData = $request->validated();
        $this->journalService->createMultiple($journalData, $request->user()->id);

        return response()->json(['message' => 'Журнал сохранен!'], 201);
    }
}
