<?php

use App\Models\Comment;
use App\Models\Course;
use App\Models\Image;
use App\Models\Permission;
use App\Models\Preference;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// ONE-TO-ONE
Route::get('/one-to-one', function(){
    $user = User::with('preference')->find(2);

    $data = [
        'background_color' => '#fff',
    ];

    // Atualiza a preferencia
    if ($user->preference){
        $user->preference->update($data);
    // Insere a preferencia 
    } else {
        //$user->preference()->create($data);
        $preference = new Preference($data);
        $user->preference()->save($preference);
    }

    $user->refresh();

    dd($user->preference);
});

// ONE-TO-MANY
Route::get('/one-to-many', function(){
    //$course = Course::create(['name' => 'Curso de Laravel']);
    $course = Course::with('modules.lessons')->first();

    echo $course->name;
    echo '<br>';
    foreach ($course->modules as $module) {
        echo "Módulo {$module->name} <br>";

        foreach ($module->lessons as $lesson) {
            echo "Aula {$lesson->name} <br>";
        } 
    }    

    $data = [
        'name' => 'Módulo x2'
    ];
    // $course->modules()->create($data);

    // $course->modules()->get();
    $modules = $course->modules;

    dd($modules);
});

// MANY-TO-MANY
Route::get('/many-to-many', function(){
    $user = User::with('permissions')->find(1);

    // $permission = Permission::find(1);
    // $user->permissions()->save($permission);
    // $user->permissions()->saveMany([
    //     Permission::find(1),
    //     Permission::find(2),
    //     Permission::find(3),
    // ]); 
    // $user->permissions()->sync([2]);
    // $user->permissions()->attach([1,3]);
    $user->permissions()->detach([1,3]);

    $user->refresh();

    dd($user->permissions);
});

// MANY-TO-MANY-PIVOT
Route::get('/many-to-many-pivot', function(){
    $user = User::with('permissions')->find(1);
    // $user->permissions()->attach([
    //     1 => ['active' => false],
    //     3 => ['active' => false],
    // ]);
    // $user->refresh();

    echo "<b>{$user->name}</b><br>";
    foreach ($user->permissions as $permission) {
        echo "{$permission->name} - {$permission->pivot->active}<br>";
    }
});

// ONE-TO-ONE-POLYMORPHIC
Route::get('/one-to-one-polymorphic', function(){
    $user = User::first();

    $data = ['path' => 'path/nome-image2.png'];

    if ($user->image){
        $user->image->update($data);
    } else {
        $user->image()->create($data);
    }

    dd($user->image->path);
});

// ONE-TO-MANY-POLYMORPHIC
Route::get('/one-to-many-polymorphic', function(){
    // $course = Course::first();

    // $course->comments()->create([
    //     'subject' => 'Novo Comentário 2',
    //     'content' => 'Apenas um comentário legal 2',
    // ]);
    
    // dd($course->comments);

    $comment = Comment::find(1);
    dd($comment);
});

// MANY-TO-MANY-POLYMORPHIC
Route::get('/many-to-many-polymorphic', function(){
    // Tag::create(['name' => 'tag1', 'color' => 'blue']);
    // Tag::create(['name' => 'tag2', 'color' => 'red']);
    // Tag::create(['name' => 'tag3', 'color' => 'green']);

    // $course = Course::first();
    // $course->tags()->attach(2);
    // dd($course->tags);

    $tag = Tag::where('name', 'tag3')->first();
    dd($tag->users);
});