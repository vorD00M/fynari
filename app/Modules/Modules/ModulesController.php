<?php

namespace Fylari\Modules\Modules;

use Fylari\Core\Controller;
use Fylari\Core\DB;

class ModulesController extends Controller
{
    public function index()
    {
        $modules = DB::table('modules')->get();
        $this->json($modules);
    }

    public function update($id)
    {
        $data = $this->input();
        $module = DB::table('modules')->where('id', '=', $id)->first();
        print_r($module);
        if (!$module) {
            $this->json(['error' => 'Module not found'], 404);
            return;
        }

        $fields = [];

        // Всегда разрешены
        if (isset($data['name']))        $fields['name'] = $data['name'];
        if (isset($data['description'])) $fields['description'] = $data['description'];
        if (isset($data['active']))      $fields['active'] = (int) $data['active'];
        if (isset($data['icon'])) {
            $fields['icon'] = preg_replace('/[^A-Za-z0-9]/', '', $data['icon']);
        }
        if (isset($data['show_in_menu'])) {
            $fields['show_in_menu'] = (int) $data['show_in_menu'];
        }
        if (isset($data['menu_category'])) $fields['menu_category'] = $data['menu_category'];
        if (isset($data['menu_order'])) $fields['menu_order'] = (int) $data['menu_order'];

        // Только если тип entity
        if ($module['type'] === 'entity') {
            if (isset($data['doc_prefix'])) {
                $fields['doc_prefix'] = preg_replace('/[^A-Z0-9]/i', '', strtoupper($data['doc_prefix']));
            }
            if (isset($data['doc_scope']) && in_array($data['doc_scope'], ['global', 'yearly', 'monthly', 'daily'])) {
                $fields['doc_scope'] = $data['doc_scope'];
            }
        }

        // Тип менять нельзя
        if (isset($data['type']) && $data['type'] !== $module['type']) {
            $this->json(['error' => 'Module type is not editable'], 400);
            return;
        }

        if (empty($fields)) {
            $this->json(['error' => 'Nothing to update'], 400);
            return;
        }

        DB::table('modules')->where('id', '=',$id)->update($fields);
        $this->json(['message' => 'Module updated']);
    }

}
