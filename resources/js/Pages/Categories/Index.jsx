import React, { useContext, useEffect, useState } from "react";
import Helmet from "react-helmet";
import { InertiaLink, usePage } from "@inertiajs/inertia-react";
import { BaseLayout as Layout } from "@/Components/Layouts";
import { Button, Tree } from "antd";
import "antd/dist/antd.css";
import { Inertia } from "@inertiajs/inertia";
import { loop } from "@/utils";
import { ArrowDownOutlined } from "@ant-design/icons";
import { MainContext } from "@/Contexts/MainContext";
import Pagination from "../../Components/Pagination/Pagination";

/**
 * TODO: Add async data loading
 * Need to use different visit parameters in onSelect and onLoadData or
 * they both get triggered at roughly the same time
 * https://ant.design/components/tree/
 */
export default () => {
  const { categories, category } = usePage().props;
  const [data, setData] = useState(categories);
  const { locale } = useContext(MainContext);

  const onDrop = (info, categories, setCategories) => {
    const dropKey = info.node.key;
    const dragKey = info.dragNode.key;
    const dropPos = info.node.pos.split("-");
    const dropPosition =
      info.dropPosition - Number(dropPos[dropPos.length - 1]);

    const data = [...categories];

    // Find dragObject
    let dragObj;
    loop(data, dragKey, (item, index, arr) => {
      arr.splice(index, 1);
      dragObj = item;
    });

    if (!info.dropToGap) {
      // Drop on the content
      loop(data, dropKey, (item) => {
        item.children = item.children || [];
        // where to insert
        item.children.unshift(dragObj);
      });
    } else if (
      (info.node.props.children || []).length > 0 && // Has children
      info.node.props.expanded && // Is expanded
      dropPosition === 1 // On the bottom gap
    ) {
      loop(data, dropKey, (item) => {
        item.children = item.children || [];
        // where to insert
        item.children.unshift(dragObj);
      });
    } else {
      let ar, i;
      loop(data, dropKey, (item, index, arr) => {
        (ar = arr), (i = index);
      });
      if (dropPosition === -1) {
        ar.splice(i, 0, dragObj);
      } else {
        ar.splice(i + 1, 0, dragObj);
      }
    }

    setCategories(data);
    // TODO: Save the new category structure (order is not kept atm)
    Inertia.post(route("categories.updateTree"), data);
  };

  const onSelect = (keys, info) => {
    Inertia.visit(
      route("categories.index", {
        category: info.node.key,
      }),
      { only: ["category"] }
    );
  };

  function updateTreeData(list, key, children) {
    return list.map((node) => {
      if (node.key === key) {
        return { ...node, children };
      } else if (node.children) {
        return {
          ...node,
          children: updateTreeData(node.children, key, children),
        };
      }

      return node;
    });
  }

  const onLoadData = ({ key, children }) => {
    return new Promise((resolve) => {
      if (children) {
        resolve();
        return;
      }

      Inertia.visit(
        route(
          "categories.index",
          { category: key },
          {
            only: ["category"],
            onSuccess: (response) => {
              const { category } = response.props;
              setData((origin) =>
                updateTreeData(origin, key, category.children)
              );
              resolve();
              return;
            },
          }
        )
      );
    });
  };

  return (
    <Layout>
      <Helmet title="Categories" />
      <div className="flex">
        <div className="w-3/12">
          <h1 className="mb-8 text-3xl font-bold">Categories</h1>
          <Tree
            autoExpandParent={true}
            blockNode
            draggable
            loadData={(props) => onLoadData(props)}
            defaultExpandedKeys={category && [category.key]}
            defaultSelectedKeys={category && [category.key]}
            onDrop={(info) => onDrop(info, data, setData)}
            onSelect={(keys, info) => onSelect(keys, info)}
            switcherIcon={<ArrowDownOutlined style={{ fontSize: 16 }} />}
            titleRender={(node) => `${node.title[locale]}`}
            treeData={data}
          />
        </div>
        {category && (
          <div className="w-9/12 pl-4">
            {category.canEdit && (
              <>
                <InertiaLink href={route("categories.edit", category.key)}>
                  <div className="my-8 text-3xl font-bold">
                    Edit category {category.key}
                  </div>
                </InertiaLink>
              </>
            )}
            {!category.canEdit && (
              <h2 className="my-8 text-3xl font-bold">
                Can't edit this category
              </h2>
            )}
            {!category.disabled && (
              <>
                <div className="grid grid-cols-3">
                  {category.products.data.map(({ name, description }, key) => (
                    <div className="px-2" key={key}>
                      <div>{name[locale]}</div>
                      <div>{description[locale]}</div>
                      {/* <img
                        src={
                          category.key % 2 === 0
                            ? "storage/sample_1280x853.jpg"
                            : "storage/wakeupcat.jpg"
                        }
                        lazy="true"
                      /> */}
                    </div>
                  ))}
                </div>
                <Pagination
                  links={category.products.links}
                  currentPage={category.products.currentPage}
                  lastPage={category.products.lastPage}
                />
              </>
            )}
          </div>
        )}
      </div>
    </Layout>
  );
};
