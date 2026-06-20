<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background: #f8fafc; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1rem; }
        .form-card { max-width: 800px; width: 100%; background: white; border-radius: 2rem; box-shadow: 0 20px 60px rgba(0,0,0,0.08); padding: 2.5rem; }
        .form-control { border-radius: 12px; border: 1px solid #e2e8f0; padding: 0.75rem 1rem; transition: all 0.2s; }
        .form-control:focus { border-color: #6c5ce7; box-shadow: 0 0 0 4px rgba(108,92,231,0.12); outline: none; }
        .btn-submit { background: linear-gradient(135deg, #6c5ce7, #4a3db8); border: none; padding: 0.75rem 2rem; border-radius: 60px; font-weight: 600; color: white; transition: all 0.3s; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(108,92,231,0.3); }
    </style>
</head>
<body>
    <div class="form-card">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $form->title }}</h1>
        @if($form->description)
            <p class="text-gray-600 mb-6">{{ $form->description }}</p>
        @endif

        <form method="POST" action="{{ route('public.form.submit', $form->shareable_link) }}">
            @csrf
            @foreach($form->fields as $field)
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        {{ $field->label }}
                        @if($field->required) <span class="text-red-500">*</span> @endif
                    </label>

                    @switch($field->type)
                        @case('text')
                        @case('email')
                        @case('phone')
                            <input type="{{ $field->type === 'email' ? 'email' : ($field->type === 'phone' ? 'tel' : 'text') }}"
                                   name="{{ $field->id }}"
                                   class="form-control w-full"
                                   placeholder="{{ $field->placeholder }}"
                                   @if($field->required) required @endif>
                            @break

                        @case('textarea')
                            <textarea name="{{ $field->id }}"
                                      class="form-control w-full"
                                      rows="4"
                                      placeholder="{{ $field->placeholder }}"
                                      @if($field->required) required @endif></textarea>
                            @break

                        @case('dropdown')
                            <select name="{{ $field->id }}"
                                    class="form-control w-full"
                                    @if($field->required) required @endif>
                                <option value="">Select...</option>
                                @foreach($field->options ?? [] as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                            @break

                        @case('checkbox')
                            <div class="flex items-center">
                                <input type="checkbox" name="{{ $field->id }}"
                                       class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                                       value="1"
                                       @if($field->required) required @endif>
                                <label class="ml-2 text-sm text-gray-700">Yes</label>
                            </div>
                            @break

                        @case('radio')
                            @foreach($field->options ?? [] as $option)
                                <div class="flex items-center">
                                    <input type="radio" name="{{ $field->id }}" value="{{ $option }}"
                                           class="border-gray-300 text-purple-600 focus:ring-purple-500"
                                           @if($field->required) required @endif>
                                    <label class="ml-2 text-sm text-gray-700">{{ $option }}</label>
                                </div>
                            @endforeach
                            @break

                        @case('date')
                            <input type="date" name="{{ $field->id }}"
                                   class="form-control w-full"
                                   @if($field->required) required @endif>
                            @break

                        @case('file')
                            <input type="file" name="{{ $field->id }}"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                                   @if($field->required) required @endif>
                            @break

                        @case('rating')
                            <div class="flex gap-2 text-3xl text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <label>
                                        <input type="radio" name="{{ $field->id }}" value="{{ $i }}" class="hidden peer">
                                        <i class="fas fa-star peer-checked:text-yellow-400 text-gray-300 hover:text-yellow-400 cursor-pointer"></i>
                                    </label>
                                @endfor
                            </div>
                            @break

                        @case('signature')
                            <div class="border-2 border-dashed rounded-lg p-6 text-center text-gray-400">
                                <i class="fas fa-pen text-3xl"></i>
                                <p class="text-sm mt-1">Sign here (touch or mouse)</p>
                            </div>
                            @break

                        @default
                            <input type="text" name="{{ $field->id }}" class="form-control w-full">
                    @endswitch
                </div>
            @endforeach

            <button type="submit" class="btn-submit">Submit</button>
        </form>

        @if(session('success'))
            <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
    </div>
</body>
</html>