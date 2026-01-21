@extends('layouts.app')

@section('content')
<div class="container">
    <h1>タスク管理</h1>

    <input id="title" placeholder="タスク名">
    <input id="description" placeholder="詳細">

    <select id="status">
        <option value="todo">todo</option>
        <option value="doing">doing</option>
        <option value="done">done</option>
    </select>

    <button id="add-btn">追加</button>

    <table id="task-table">
        <thead>
            <tr>
                <th>タイトル</th>
                <th>詳細</th>
                <th>状態</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody id="task-list"></tbody>
    </table>
</div>

{{-- JS --}}
<script type="module" src="{{ asset('js/main.js') }}"></script>
@endsection
