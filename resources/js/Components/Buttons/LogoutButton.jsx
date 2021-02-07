import React from "react";
import { InertiaLink } from "@inertiajs/inertia-react";

export default ({props}) => {
  return (
    <InertiaLink
      href={route("logout")}
      className="block px-6 py-2 hover:bg-indigo-600 hover:text-white"
      method="post"
      as="button"
      {...props}
    >
      Logout
    </InertiaLink>
  );
};
