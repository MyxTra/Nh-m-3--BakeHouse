<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Core\Auth;
use App\Core\ErrorHandler;
use App\Core\Paginator;
use App\Core\Request;
use App\Core\Response;
use App\Repositories\UserRepository;

/** Quản lý người dùng: xem danh sách, đổi vai trò, khóa/mở tài khoản. */
final class UserController extends BaseController
{
    public function __construct(private readonly UserRepository $users = new UserRepository())
    {
    }

    public function index(Request $request): Response
    {
        $search = trim((string) $request->getQuery('q', ''));
        $role = (string) $request->getQuery('role', '');
        $active = (string) $request->getQuery('active', '');
        $paginator = Paginator::fromRequest($request, $this->users->adminCount($search, $role, $active), 10);

        return $this->render('admin.users.index', [
            'title' => 'Người dùng',
            'rows' => $this->users->adminList($search, $role, $active, $paginator->getLimit(), $paginator->getOffset()),
            'paginator' => $paginator,
            'search' => $search,
            'role' => $role,
            'active' => $active,
        ], 'layouts.admin');
    }

    public function show(Request $request, string $id): Response
    {
        $user = $this->users->findById((int) $id);
        if ($user === null) {
            return ErrorHandler::render(404, $request);
        }

        return $this->render('admin.users.show', [
            'title' => 'Chi tiết người dùng',
            'user' => $user,
            'activity' => $this->users->activitySummary((int) $id),
            'isSelf' => Auth::id() === (int) $id,
        ], 'layouts.admin');
    }

    public function updateRole(Request $request, string $id): Response
    {
        $user = $this->users->findById((int) $id);
        if ($user === null) {
            return ErrorHandler::render(404, $request);
        }

        $target = '/admin/users/' . (int) $id . $this->filterQuerySuffix($request);

        if (Auth::id() === (int) $id) {
            $this->flash('danger', 'Không thể tự đổi vai trò của chính mình.');

            return $this->redirect($target);
        }

        $role = (string) $request->getInput('role', '');
        if (!in_array($role, ['user', 'admin'], true)) {
            $this->flash('danger', 'Vai trò không hợp lệ.');

            return $this->redirect($target);
        }

        $this->users->setRole((int) $id, $role);
        $this->flash('success', 'Đã cập nhật vai trò.');

        return $this->redirect($target);
    }

    public function toggleActive(Request $request, string $id): Response
    {
        $user = $this->users->findById((int) $id);
        if ($user === null) {
            return ErrorHandler::render(404, $request);
        }

        $target = '/admin/users/' . (int) $id . $this->filterQuerySuffix($request);

        if (Auth::id() === (int) $id) {
            $this->flash('danger', 'Không thể tự khóa tài khoản của chính mình.');

            return $this->redirect($target);
        }

        $nextActive = !((int) $user['is_active'] === 1);
        $this->users->setActive((int) $id, $nextActive);
        $this->flash('success', $nextActive ? 'Đã mở khóa tài khoản.' : 'Đã khóa tài khoản.');

        return $this->redirect($target);
    }

    private function filterQuerySuffix(Request $request): string
    {
        $q = trim((string) ($request->getInput('q', $request->getQuery('q', '')) ?? ''));

        $roleRaw = (string) ($request->getInput('role', $request->getQuery('role', '')) ?? '');
        $role = in_array($roleRaw, ['user', 'admin'], true) ? $roleRaw : '';

        $activeRaw = (string) ($request->getInput('active', $request->getQuery('active', '')) ?? '');
        $active = in_array($activeRaw, ['0', '1'], true) ? $activeRaw : '';

        $pageRaw = (string) ($request->getInput('page', $request->getQuery('page', '1')) ?? '1');
        $page = ctype_digit($pageRaw) && (int) $pageRaw > 1 ? (int) $pageRaw : 1;

        $params = array_filter([
            'q' => $q !== '' ? $q : null,
            'role' => $role !== '' ? $role : null,
            'active' => $active !== '' ? $active : null,
            'page' => $page > 1 ? $page : null,
        ], static fn (mixed $v): bool => $v !== null);

        return $params !== [] ? '?' . http_build_query($params) : '';
    }
}