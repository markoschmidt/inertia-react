import React, { useState } from "react";
import Icon from "../Icon";

export default ({ children, containerClasses, icon, iconClass }) => {
  const [menuOpened, setMenuOpened] = useState(false);

  const iconElement = children.length ? children.filter(child => child.key === 'icon') : null;
  const childElements = children.length ? children.filter(child => child.key !== 'icon') : null;

  return (
    <div className={`relative ${containerClasses || ""}`}>
      <div onClick={setMenuOpened}>
        {iconElement}
      </div>
      <div className={`${menuOpened ? "" : "hidden"} absolute right-0 z-20`}>
        {childElements}
        <div
          onClick={() => {
            setMenuOpened(false);
          }}
          className="fixed inset-0 z-10 bg-black opacity-25"
        ></div>
      </div>
    </div>
  );
};
