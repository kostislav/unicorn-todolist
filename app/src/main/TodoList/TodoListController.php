<?php

namespace TodoList;

use Unicorn\Form\SubmittedForm;
use Unicorn\Routing\GET;
use Unicorn\Routing\POST;
use Unicorn\Routing\UrlParam;
use Unicorn\Http\Response;

class TodoListController {
    public function __construct(
        private readonly TodoListService $todoListService
    ) {
    }

    #[GET('/')]
    public function index(): Response {
        return Response::plain("Hello world!");
//        return $this->showForm(new TodoForm());
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
//    private function showForm(TodoForm $form): Response {
//        return Response::template('todo', [
//            'form' => $form,
//            'items' => $this->todoListService->getAllUnfinished()
//        ]);
//    }
}