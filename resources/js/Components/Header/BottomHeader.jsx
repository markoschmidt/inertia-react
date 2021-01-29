import React, { useState } from 'react';
import { InertiaLink, usePage } from '@inertiajs/inertia-react';
import Icon from '@/Components/Icon/Icon';

export default () => {
  const { auth } = usePage().props;
  const [menuOpened, setMenuOpened] = useState(false);
  return (
    <div className="bg-white border-b w-full p-4 md:py-0 md:px-12 text-sm d:text-md flex justify-end items-center">
      <div className="relative">
        <div
          className="flex items-center cursor-pointer select-none group"
          onClick={() => setMenuOpened(true)}
        >
          <div className="text-gray-800 group-hover:text-indigo-600 focus:text-indigo-600 mr-1 whitespace-no-wrap">
            <span>{auth.user.name}</span>
          </div>
          <Icon
            className="w-5 h-5 fill-current text-gray-800 group-hover:text-indigo-600 focus:text-indigo-600"
            name="cheveron-down"
          />
        </div>
        <div className={menuOpened ? '' : 'hidden'}>
          <div className="whitespace-no-wrap absolute z-20 mt-8 left-auto top-0 right-0 py-2 shadow-xl bg-white rounded text-sm">
            <InertiaLink
              href={route('logout')}
              className="block px-6 py-2 hover:bg-indigo-600 hover:text-white"
              method="post"
              as="button"
            >
              Logout
            </InertiaLink>
          </div>
          <div
            onClick={() => {
              setMenuOpened(false);
            }}
            className="bg-black opacity-25 fixed inset-0 z-10"
          ></div>
        </div>
      </div>
    </div>
  );
};
