<?php

namespace Webkul\Admin\Http\Controllers\Projects;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Prettus\Repository\Criteria\RequestCriteria;
use Webkul\Admin\DataGrids\Project\ProjectCategoryDataGrid;
use Webkul\Admin\DataGrids\Project\ProjectDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\AttributeForm;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Resources\ProjectResource;
use Webkul\Project\Repositories\ProjectCategoryRepository;

class ProjectCategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ProjectCategoryRepository $projectCategoryRepository)
    {
        request()->request->add(['entity_type' => 'projects-categories']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(ProjectCategoryDataGrid::class)->process();
        }

        return view('admin::projects.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin::projects.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeForm $request)
    {
        Event::dispatch('projects.categories.create.before');

        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $projectCategory = $this->projectCategoryRepository->create($data);

        Event::dispatch('projects.categories.create.after', $projectCategory);

        session()->flash('success', trans('admin::app.projects.categories.index.create-success'));

        return redirect()->route('admin.projects.categories.index');
    }

    /**
     * Show the form for viewing the specified resource.
     */
    public function view(int $id): View
    {
        $projectCategory = $this->projectCategoryRepository->findOrFail($id);

        return view('admin::projects.categories.view', compact('projectCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View|JsonResponse
    {
        $projectCategory = $this->projectCategoryRepository->findOrFail($id);
        return view('admin::projects.categories.edit', compact('projectCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeForm $request, int $id)
    {
        Event::dispatch('project.categories.update.before', $id);

        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $projectCategory = $this->projectCategoryRepository->update($data, $id);

        Event::dispatch('project.categories.update.after', $projectCategory);

        if (request()->ajax()) {
            return response()->json([
                'message' => trans('admin::app.projects.categories.index.update-success'),
            ]);  
        }

        session()->flash('success', trans('admin::app.projects.categories.index.update-success'));

        return redirect()->route('admin.projects.categories.index');
    }

    /**
     * Search project results
     */
    public function search(): JsonResource
    {
        $projectCategories = $this->projectCategoryRepository
            ->pushCriteria(app(RequestCriteria::class))
            ->all();

        return ProjectResource::collection($projectCategories);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $projectCategories = $this->projectCategoryRepository->findOrFail($id);

        try {
            Event::dispatch('settings.projects.categories.delete.before', $id);

            $projectCategories->delete($id);

            Event::dispatch('settings.projects.categories.delete.after', $id);

            return new JsonResponse([
                'message' => trans('admin::app.projects.categories.index.delete-success'),
            ], 200);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => trans('admin::app.projects.categories.index.delete-failed'),
            ], 400);
        }
    }

    /**
     * Mass Delete the specified resources.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $indices = $massDestroyRequest->input('indices');

        foreach ($indices as $index) {
            Event::dispatch('project.categories.delete.before', $index);

            $this->projectCategoryRepository->delete($index);

            Event::dispatch('project.categories.delete.after', $index);
        }

        return new JsonResponse([
            'message' => trans('admin::app.projects.categories.index.delete-success'),
        ]);
    }
}
