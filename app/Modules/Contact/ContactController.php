<?php

namespace App\Modules\Contact;

use Core\Controller;

class ContactController extends Controller
{
    private Contact $model;

    public function __construct()
    {
        $this->model = new Contact();
    }

    public function index(): void
    {
        $contacts = $this->model->all();
        $this->json($contacts);
    }

    public function show($id): void
    {
        $contact = $this->model->find((int)$id);
        if (!$contact) {
            $this->json(['error' => 'Contact not found'], 404);
        } else {
            $this->json($contact);
        }
    }

    public function store(): void
    {
        $data = $this->input();
        $success = $this->model->create($data);
        $this->json(['success' => $success]);
    }

    public function update($id): void
    {
        $data = $this->input();
        $success = $this->model->update((int)$id, $data);
        $this->json(['success' => $success]);
    }

    public function delete($id): void
    {
        $success = $this->model->delete((int)$id);
        $this->json(['success' => $success]);
    }
}

