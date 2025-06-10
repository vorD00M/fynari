import { useRef } from 'react';

type ToggleProps = {
    checked: boolean;
    onChange: (value: boolean) => void;
};

export default function Toggle({ checked, onChange }: ToggleProps) {
    const rippleRef = useRef<HTMLSpanElement>(null);

    const handleClick = () => {
        onChange(!checked);
        const ripple = rippleRef.current;
        if (ripple) {
            ripple.classList.remove('animate-ripple');
            void ripple.offsetWidth; // force reflow
            ripple.classList.add('animate-ripple');
        }
    };

    return (
        <button
            type="button"
            onClick={handleClick}
            className={`relative inline-flex h-7 w-12 items-center rounded-full border transition-colors duration-300
        ${checked
                ? 'bg-white/10 border-blue-400 backdrop-blur-md shadow-[0_0_2px_1px_rgba(0,123,255,0.6)]'
                : 'bg-white/5 border-gray-500 backdrop-blur-sm'}
      `}
        >
            {/* Ripple */}
            <span
                ref={rippleRef}
                className="absolute left-0 top-0 w-full h-full rounded-full opacity-40 bg-blue-400/30 pointer-events-none"
            ></span>

            {/* Handle */}
            <span
                className={`inline-block h-5 w-5 rounded-full transition-transform duration-300
          ${checked ? 'translate-x-6 bg-blue-500/80 border-blue-500' : 'translate-x-1 bg-blue-500/30 border-blue-500/50'}
          shadow-lg backdrop-blur-md border`}
            />
        </button>
    );
}
