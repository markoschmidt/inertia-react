import React from "react";
import { InertiaLink } from "@inertiajs/inertia-react";

export default ({ props, route, className, method = "post" }) => {
  return (
    <InertiaLink
      href={route}
      className={`block px-6 py-2 hover:bg-indigo-600 hover:text-white ${className || ''}`}
      method={method}
      as="button"
      {...props}
    >
      Logout
    </InertiaLink>
  );
};
