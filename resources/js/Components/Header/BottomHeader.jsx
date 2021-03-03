import React, { useContext } from "react";
import { usePage } from "@inertiajs/inertia-react";
import Icon from "@/Components/Icon";
import Dropdown from "@/Components/Dropdown";
import { LinkButton } from "@/Components/Buttons";
import { MainContext } from "../../Contexts/MainContext"

export default () => {
  const { auth } = usePage().props;
  const { locale, toggleLocale } = useContext(MainContext);

  return (
    <div className="flex items-center justify-end w-full p-4 text-sm bg-white border-b md:py-0 md:px-12 d:text-md">
      <button className="px-4 py-1 mr-4 text-white bg-indigo-700 border rounded hover:bg-gray-500 focus-within:bg-gray-500" onClick={() => toggleLocale()}>{locale}</button>
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
