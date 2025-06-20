<?php

namespace Fylari\Modules\Contact;

use Fylari\Core\Controller;
use Fylari\Core\Entity;
use Fylari\Core\Field;
use Fylari\Core\DB;

class ContactController extends Controller
{
    private Field $field;
    private Entity $entity;
    private int $moduleId = 1;

    public function __construct()
    {
        $this->field = new Field();
        $this->entity = new Entity();
    }

    public function index(): void
    {
        $this->json($this->entity->all($this->moduleId));
    }

    public function show($id): void
    {
        $this->json($this->entity->find($id));
    }

    public function store(): void
    {
        $this->json(['message' => 'Contact store logic here']);
    }

    public function update($id): void
    {
        $this->json(['message' => 'Contact update logic here']);
    }

    public function destroy($id): void
    {
        $this->entity->delete($id, 1);
        $this->json(['success' => true]);
    }

    public function archive($id): void
    {
        $this->entity->archive($id, 1);
        $this->json(['archived' => true]);
    }

    public function restore($id): void
    {
        $this->entity->restore($id, 1);
        $this->json(['restored' => true]);
    }
}
