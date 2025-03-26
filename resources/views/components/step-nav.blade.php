@props(['steps' => [], 'currentStep' => 1])

<div class="mb-10">
    <div class="flex items-center justify-between">
        <div class="w-full flex items-center">
            @foreach($steps as $index => $step)
                <x-step-button 
                    :number="$index + 1" 
                    :active="$currentStep >= $index + 1" 
                    :text="$step" 
                />
                
                @if($index < count($steps) - 1)
                    <div class="flex-auto border-t-2 transition duration-500 ease-in-out 
                        {{ $currentStep > $index + 1 ? 'border-accent-primary' : 'border-gray-600/30' }}">
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>