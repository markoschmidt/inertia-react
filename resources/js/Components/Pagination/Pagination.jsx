import React, { useState } from "react";
import { InertiaLink } from "@inertiajs/inertia-react";
import classNames from "classnames";
import { InputNumber } from "antd";
import { Inertia } from "@inertiajs/inertia";

const PageLink = ({ active, label, url, currentPage, lastPage }) => {
  const className = classNames(
    [
      "mr-1 ml-1",
      "px-4 py-3",
      "border rounded",
      "text-sm",
      "hover:bg-white",
      "focus:border-indigo-700 focus:text-indigo-700",
    ],
    {
      "bg-white": active,
    }
  );
  const disabled =
    (label === "First" && currentPage === 1) ||
    (label === "Last" && currentPage === lastPage);
  return (
    <InertiaLink
      className={className}
      disabled={disabled}
      href={url}
      only={["category"]}
    >
      {label}
    </InertiaLink>
  );
};

// Previous, if on first page
// Next, if on last page
// and dots, if exists (...)
const PageInactive = ({ label }) => {
  const className = classNames(
    "mr-1 px-4 py-3 text-sm border rounded text-gray",
  );
  return <div className={className}>{label}</div>;
};

const PageInput = ({ currentPage, lastPage }) => {
  const className = classNames(
    `mr-1 px-4 py-3 text-sm border rounded text-dark`
  );

  const [page, setPage] = useState(1);
  const baseUrl = location.origin + location.pathname;
  const params = new URLSearchParams(location.search);
  params.set("page", page);

  return (
    <InputNumber
      className={className}
      defaultValue={currentPage}
      max={lastPage}
      min={1}
      size="large"

      onChange={(value) => setPage(Math.min(value, lastPage))}
      onPressEnter={() => Inertia.visit(baseUrl + `?${params}`, { only: ["category"], preserveScroll: true })}
      placeholder="Page"
    />
  );
};

export default ({ links = [], currentPage = 1, lastPage = 1 }) => {
  if (lastPage === 1) return null;

  return (
    <div className="flex flex-wrap justify-center mt-6 -mb-1">
      {links.map(({ active, label, url }) => {
        return label === "Current" ? (
          <PageInput key={label} currentPage={currentPage} lastPage={lastPage} />
        ) : (
          <PageLink
            key={label}
            label={label}
            active={active}
            url={url}
            currentPage={currentPage}
            lastPage={lastPage}
          />
        );
      })}
    </div>
  );
};
