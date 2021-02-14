import React, { useContext, useState } from "react";
import { InertiaLink, usePage } from "@inertiajs/inertia-react";
import Icon from "@/Components/Icon";
import Dropdown from "@/Components/Dropdown";
import { LogoutButton, LinkButton } from "@/Components/Buttons";
import { MainContext } from "../../Contexts/MainContext"

export default () => {
  const { auth } = usePage().props;
  const [menuOpened, setMenuOpened] = useState(false);
  const { locale, toggleLocale } = useContext(MainContext);

  return (
    <div className="flex items-center justify-end w-full p-4 text-sm bg-white border-b md:py-0 md:px-12 d:text-md">
      <button className="px-4" onClick={() => toggleLocale()}>{locale}</button>
      <Dropdown>
        <div
          key="icon"
          className="flex items-center cursor-pointer select-none group"
        >
          <div className="mr-1 text-gray-800 whitespace-no-wrap group-hover:text-indigo-600 focus:text-indigo-600">
            <span>{auth.user.name || auth.user.email}</span>
          </div>
          <Icon
            className="w-5 h-5 text-gray-800 fill-current group-hover:text-indigo-600 focus:text-indigo-600"
            name="cheveron-down"
          />
        </div>
        <div className="absolute top-0 right-0 left-auto z-20 py-2 mt-8 text-sm whitespace-no-wrap bg-white rounded shadow-xl">
          <LinkButton route={route('logout')} />
        </div>
      </Dropdown>
    </div>
  );
};
