<?php

namespace TodoList;

//use Unicorn\Form\SubmittedForm;
use Unicorn\Routing\GET;
use Unicorn\Routing\POST;
//use Unicorn\Routing\UrlParam;
use Unicorn\Http\Response;
use Unicorn\Routing\RequestParam;

class TodoListController {
    public function __construct(
        private readonly TodoListService $todoListService
    ) {
    }

    #[GET('/')]
    public function index(): Response {
        return $this->showForm(new TodoForm());
    }

    #[POST('/')]
    public function add(#[RequestParam] string $name): Response {
        $this->todoListService->create($name);
        return Response::redirect('index');
    }

//    #[POST('/')]
//    public function add(#[Form(TodoForm::class)] SubmittedForm $form): Response {
//        if ($form->isValid()) {
//            $this->todoListService->create($form->data->name);
//            return Response::redirect('index');
//        } else {
//            return $this->showForm($form->data);
//        }
//    }
//
//    #[POST('/{id}')]
//    public function markComplete(#[UrlParam] int $id): Response {
//        $this->todoListService->markComplete($id);
//        return Response::redirect('index');
//    }
//
    private function showForm(TodoForm $form): Response {
        return Response::template('todo', [
            'form' => $form,
            'items' => $this->todoListService->getAllUnfinished()
        ]);
    }
}