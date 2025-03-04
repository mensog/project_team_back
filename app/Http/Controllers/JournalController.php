<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJournalRequest;
use App\Http\Requests\UpdateJournalRequest;
use App\Http\Resources\JournalResource;
use App\Models\Journal;
use App\Services\Interfaces\JournalServiceInterface;
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
    public function index()
    {
        return JournalResource::collection($this->journalService->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJournalRequest $request)
    {
        $journalData = $request->validated();
        $journal = $this->journalService->create($journalData);

        return new JournalResource($journal);
    }

    /**
     * Display the specified resource.
     */
    public function show(Journal $journal)
    {
        return new JournalResource($journal);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJournalRequest $request, Journal $journal)
    {
        $journalData = $request->validated();
        $journal = $this->journalService->update($journal->id, $journalData);

        return new JournalResource($journal);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Journal $journal)
    {
        $this->journalService->delete($journal->id);

        return response()->json(null, 204);
    }
}
