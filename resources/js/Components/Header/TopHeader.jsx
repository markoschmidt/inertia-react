import React, { useState } from 'react';
import { InertiaLink } from '@inertiajs/inertia-react';
import { MainMenu } from '@/Components/MainMenu';
import Dropdown from '@/Components/Dropdown';

export default () => {
  const [menuOpened, setMenuOpened] = useState(false);
  return (
    <div className="flex items-center justify-between px-6 py-4 bg-indigo-900 md:flex-shrink-0 md:w-56 md:justify-center">
      <InertiaLink className="mt-1 text-white" href="/" as="button">
        Logo
      </InertiaLink>
      <Dropdown containerClasses="md:hidden" icon="menu" iconClass="w-6 h-6 text-white cursor-pointer fill-current">
        <MainMenu className="relative z-20 px-8 py-4 pb-2 mt-2 bg-indigo-800 rounded shadow-lg" />
      </Dropdown>
    </div>
  );
};
